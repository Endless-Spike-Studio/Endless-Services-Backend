<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountFriend;
use App\EndlessServer\Models\AccountFriendRequest;
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
use Illuminate\Support\Carbon;
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

		$account->friendRequests()
			->create([
				'target_account_id' => $targetAccount->id,
				'comment' => Base64Url::decode($data['comment']),
				'readed' => false
			]);

		return GeometryDashResponses::ACCOUNT_FRIEND_REQUEST_SEND_SUCCESS->value;
	}

	public function list(GameAccountFriendRequestListRequest $request): string
	{
		$data = $request->validated();

		Carbon::setLocale('en');

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

		return implode('#', [
			$paginate->items->map(function (AccountFriendRequest $friendRequest) use ($data, $getSent) {
				$targetAccount = $friendRequest->account;

				if ($getSent) {
					$targetAccount = $friendRequest->targetAccount;
				}

				$targetAccountPlayer = $this->accountService->queryAccountPlayer($targetAccount);

				$this->playerDataService->initialize($targetAccountPlayer->id);
				$this->playerStatisticService->initialize($targetAccountPlayer->id);

				return $this->objectService->merge([
					GeometryDashAccountFriendRequestObjectDefinition::TARGET_NAME->value => $targetAccountPlayer->name,
					GeometryDashAccountFriendRequestObjectDefinition::TARGET_USER_ID->value => $targetAccountPlayer->id,
					GeometryDashAccountFriendRequestObjectDefinition::TARGET_ICON_ID->value => $targetAccountPlayer->data->icon_id,
					GeometryDashAccountFriendRequestObjectDefinition::TARGET_COLOR_ID->value => $targetAccountPlayer->data->color1,
					GeometryDashAccountFriendRequestObjectDefinition::TARGET_SECOND_COLOR_ID->value => $targetAccountPlayer->data->color2,
					GeometryDashAccountFriendRequestObjectDefinition::TARGET_ICON_TYPE->value => $targetAccountPlayer->data->icon_type,
					GeometryDashAccountFriendRequestObjectDefinition::TARGET_SPECIAL->value => $targetAccountPlayer->data->special,
					GeometryDashAccountFriendRequestObjectDefinition::TARGET_UUID->value => $targetAccountPlayer->uuid,
					GeometryDashAccountFriendRequestObjectDefinition::ID->value => $friendRequest->id,
					GeometryDashAccountFriendRequestObjectDefinition::COMMENT->value => Base64Url::encode($friendRequest->comment, true),
					GeometryDashAccountFriendRequestObjectDefinition::AGE->value => $friendRequest->created_at->diffForHumans(syntax: true),
					GeometryDashAccountFriendRequestObjectDefinition::IS_NEW->value => !$friendRequest->readed
				], GeometryDashAccountFriendRequestObjectDefinition::GLUE);
			})->join('|'),
			$paginate->info
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
				'readed' => true
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
				'readed' => false
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