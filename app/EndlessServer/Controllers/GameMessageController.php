<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Exceptions\EndlessServerGameException;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountMessage;
use App\EndlessServer\Objects\GameMessageObject;
use App\EndlessServer\Requests\GameAccountMessageDeleteRequest;
use App\EndlessServer\Requests\GameAccountMessageDownloadRequest;
use App\EndlessServer\Requests\GameAccountMessageListRequest;
use App\EndlessServer\Requests\GameAccountMessageSendRequest;
use App\EndlessServer\Responses\GameMessageListQueryResultResponse;
use App\EndlessServer\Services\GameAccountService;
use App\EndlessServer\Services\GamePaginationService;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\GeometryDashXorKeys;
use App\GeometryDash\Enums\Objects\GeometryDashMessageObjectDefinition;
use App\GeometryDash\Services\GeometryDashAlgorithmService;
use App\GeometryDash\Services\GeometryDashObjectService;
use Base64Url\Base64Url;
use Illuminate\Support\Facades\Auth;

readonly class GameMessageController
{
	public function __construct(
		protected GamePaginationService        $paginationService,
		protected GameAccountService           $accountService,
		protected GeometryDashObjectService    $objectService,
		protected GeometryDashAlgorithmService $algorithmService
	)
	{

	}

	public function send(GameAccountMessageSendRequest $request): int
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
			return GeometryDashResponses::PLAYER_INFO_FETCH_FAILED_BLOCKED->value;
		}

		$account->messages()
			->create([
				'target_account_id' => $targetAccount->id,
				'subject' => Base64Url::decode($data['subject']),
				'body' => $this->algorithmService->xor(Base64Url::decode($data['body']), GeometryDashXorKeys::MESSAGE->value),
				'new' => true
			]);

		return GeometryDashResponses::ACCOUNT_MESSAGE_SEND_SUCCESS->value;
	}

	public function list(GameAccountMessageListRequest $request): string
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$getSent = isset($data['getSent']) && $data['getSent'] > 0;

		$paginate = $this->paginationService->generate(AccountMessage::query()
			->where($getSent ? 'account_id' : 'target_account_id', $account->id), $data['page']);

		if ($paginate->total <= 0) {
			return GeometryDashResponses::ACCOUNT_MESSAGE_LIST_FAILED_EMPTY->value;
		}

		return implode(GeometryDashMessageObjectDefinition::SEGMENTATION, [
			$paginate->items->map(function (AccountMessage $message) use ($getSent, $request) {
				return new GameMessageObject($message, $getSent)->except([
					GeometryDashMessageObjectDefinition::BODY->value
				])->merge();
			})->join(GeometryDashMessageObjectDefinition::SEPARATOR),
			$paginate->info()
		]);
	}

	public function download(GameAccountMessageDownloadRequest $request): int|string
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$getSent = isset($data['isSender']) && $data['isSender'] > 0;

		$message = AccountMessage::query()
			->where($getSent ? 'account_id' : 'target_account_id', $account->id)
			->where('id', $data['messageID'])
			->first();

		if ($message === null) {
			return GeometryDashResponses::ACCOUNT_MESSAGE_DOWNLOAD_FAILED_NOT_FOUND->value;
		}

		if (!$getSent) {
			$message->update([
				'new' => false
			]);
		}

		return new GameMessageObject($message, $getSent)->merge();
	}

	public function delete(GameAccountMessageDeleteRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$ids = collect();

		if (isset($data['messageID'])) {
			$ids->add($data['messageID']);
		}

		if (isset($data['messages'])) {
			$message_ids = explode(',', $data['messages']);
			$ids->push(...$message_ids);
		}

		AccountMessage::query()
			->where(isset($data['isSender']) ? 'account_id' : 'target_account_id', $account->id)
			->whereIn('id', $ids)
			->delete();

		return GeometryDashResponses::ACCOUNT_MESSAGE_DELETE_SUCCESS->value;
	}
}