<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountFriend;
use App\EndlessServer\Models\AccountFriendRequest;
use App\EndlessServer\Objects\GameAccountFriendRequestObject;
use App\EndlessServer\Requests\GameAccountFriendRequestAcceptRequest;
use App\EndlessServer\Requests\GameAccountFriendRequestDeleteRequest;
use App\EndlessServer\Requests\GameAccountFriendRequestListRequest;
use App\EndlessServer\Requests\GameAccountFriendRequestReadRequest;
use App\EndlessServer\Requests\GameAccountFriendRequestSendRequest;
use App\EndlessServer\Services\GameAccountService;
use App\EndlessServer\Services\GamePaginationService;
use App\EndlessServer\Services\GamePlayerDataService;
use App\EndlessServer\Services\GamePlayerStatisticService;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\Objects\GeometryDashAccountFriendRequestObjectDefinition;
use App\GeometryDash\Services\GeometryDashObjectService;
use Base64Url\Base64Url;
use Illuminate\Support\Facades\Auth;

readonly class GameAccountFriendRequestController
{
	public function __construct(
		protected GamePaginationService      $paginationService,
		protected GeometryDashObjectService  $objectService,
		protected GameAccountService         $accountService,
		protected GamePlayerDataService      $playerDataService,
		protected GamePlayerStatisticService $playerStatisticService
	)
	{

	}

	public function send(GameAccountFriendRequestSendRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$targetAccount = Account::query()
			->where('id', $data['toAccountID'])
			->first();

		$blocked = $targetAccount->blocklist()
			->where('target_account_id', $account->id)
			->exists();

		if ($blocked) {
			return GeometryDashResponses::ACCOUNT_FRIEND_REQUEST_SEND_FAILED_BLOCKED->value;
		}

		$comment = null;

		if (isset($data['comment'])) {
			$comment = Base64Url::decode($data['comment']);
		}

		$account->friendRequests()
			->create([
				'target_account_id' => $targetAccount->id,
				'comment' => $comment,
				'new' => true
			]);

		return GeometryDashResponses::ACCOUNT_FRIEND_REQUEST_SEND_SUCCESS->value;
	}

	public function list(GameAccountFriendRequestListRequest $request): string
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$page = 1;

		if (isset($data['page'])) {
			$page = $data['page'] + 1;
		}

		$query = $account->receiveFriendRequests();

		$getSent = isset($data['getSent']) && $data['getSent'] > 0;

		if ($getSent) {
			$query = $account->friendRequests();
		}

		$paginate = $this->paginationService->generate($query, $page);

		if ($paginate->total <= 0) {
			return GeometryDashResponses::ACCOUNT_FRIEND_REQUEST_LIST_EMPTY->value;
		}

		return implode(GeometryDashAccountFriendRequestObjectDefinition::SEGMENTATION, [
			$paginate->items->map(function (AccountFriendRequest $friendRequest) use ($getSent, $request) {
				return new GameAccountFriendRequestObject($friendRequest, $getSent)->merge();
			})->join(GeometryDashAccountFriendRequestObjectDefinition::SEPARATOR),
			$paginate->info()
		]);
	}

	public function read(GameAccountFriendRequestReadRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$account->receiveFriendRequests()
			->where('id', $data['requestID'])
			->update([
				'new' => false
			]);

		return GeometryDashResponses::ACCOUNT_FRIEND_REQUEST_READ_SUCCESS->value;
	}

	public function accept(GameAccountFriendRequestAcceptRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$friendRequest = $account->receiveFriendRequests()
			->where('id', $data['requestID'])
			->where('account_id', $data['targetAccountID'])
			->first();

		if ($friendRequest === null) {
			return GeometryDashResponses::ACCOUNT_FRIEND_REQUEST_ACCEPT_FAILED_NOT_FOUND->value;
		}

		AccountFriend::query()
			->create([
				'account_id' => $account->id,
				'target_account_id' => $friendRequest->account_id,
				'comment' => $friendRequest->comment,
				'alias' => null,
				'new' => true
			]);

		$friendRequest->delete();

		return GeometryDashResponses::ACCOUNT_FRIEND_REQUEST_ACCEPT_SUCCESS->value;
	}

	public function delete(GameAccountFriendRequestDeleteRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$ids = collect();

		if (isset($data['targetAccountID'])) {
			$ids->add($data['targetAccountID']);
		}

		if (isset($data['accounts'])) {
			$account_ids = explode(',', $data['accounts']);
			$ids->push(...$account_ids);
		}

		$query = $account->receiveFriendRequests();

		$isSender = isset($data['isSender']) && $data['isSender'] > 0;

		if ($isSender) {
			$query = $account->friendRequests();
		}

		$query->whereIn($isSender ? 'target_account_id' : 'account_id', $ids)->delete();

		return GeometryDashResponses::ACCOUNT_FRIEND_REQUEST_DELETE_SUCCESS->value;
	}
}