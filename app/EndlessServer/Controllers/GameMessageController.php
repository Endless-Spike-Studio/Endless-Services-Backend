<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountMessage;
use App\EndlessServer\Requests\GameAccountMessageDeleteRequest;
use App\EndlessServer\Requests\GameAccountMessageDownloadRequest;
use App\EndlessServer\Requests\GameAccountMessageListRequest;
use App\EndlessServer\Requests\GameAccountMessageSendRequest;
use App\EndlessServer\Services\GameAccountService;
use App\EndlessServer\Services\GamePaginationService;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\GeometryDashXorKeys;
use App\GeometryDash\Enums\Objects\GeometryDashMessageObjectDefinition;
use App\GeometryDash\Services\GeometryDashAlgorithmService;
use App\GeometryDash\Services\GeometryDashObjectService;
use Base64Url\Base64Url;
use Illuminate\Support\Carbon;
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
				'readed' => false
			]);

		return GeometryDashResponses::ACCOUNT_MESSAGE_SEND_SUCCESS->value;
	}

	public function list(GameAccountMessageListRequest $request): string
	{
		$data = $request->validated();

		Carbon::setLocale('en');

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$paginate = $this->paginationService->generate(AccountMessage::query()
			->where(isset($data['getSent']) ? 'account_id' : 'target_account_id', $account->id), $data['page']);

		if ($paginate->total <= 0) {
			return GeometryDashResponses::ACCOUNT_MESSAGE_LIST_FAILED_EMPTY->value;
		}

		return implode('#', [
			$paginate->items->map(function (AccountMessage $message) use ($data) {
				$targetAccount = isset($data['getSent']) ? $message->targetAccount : $message->account;

				return $this->objectService->merge([
					GeometryDashMessageObjectDefinition::ID->value => $message->id,
					GeometryDashMessageObjectDefinition::ACCOUNT_ID->value => $targetAccount->id,
					GeometryDashMessageObjectDefinition::PLAYER_ID->value => $targetAccount->player->id,
					GeometryDashMessageObjectDefinition::SUBJECT->value => Base64Url::encode($message->subject, true),
					GeometryDashMessageObjectDefinition::BODY->value => Base64Url::encode($this->algorithmService->xor($message->body, GeometryDashXorKeys::MESSAGE->value), true),
					GeometryDashMessageObjectDefinition::PLAYER_NAME->value => $targetAccount->player->name,
					GeometryDashMessageObjectDefinition::AGE->value => $message->created_at->diffForHumans(syntax: true),
					GeometryDashMessageObjectDefinition::IS_READ->value => $message->readed,
					GeometryDashMessageObjectDefinition::IS_SENDER->value => isset($data['getSent'])
				], GeometryDashMessageObjectDefinition::GLUE);
			})->join('|'),
			$paginate->info
		]);
	}

	public function download(GameAccountMessageDownloadRequest $request): int|string
	{
		$data = $request->validated();

		Carbon::setLocale('en');

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$message = AccountMessage::query()
			->where(isset($data['isSender']) ? 'account_id' : 'target_account_id', $account->id)
			->where('id', $data['messageID'])
			->first();

		if ($message === null) {
			return GeometryDashResponses::ACCOUNT_MESSAGE_DOWNLOAD_FAILED_NOT_FOUND->value;
		}

		$targetAccount = isset($data['isSender']) ? $message->account : $message->targetAccount;

		if (!isset($data['isSender'])) {
			$message->update([
				'readed' => true
			]);
		}

		return $this->objectService->merge([
			GeometryDashMessageObjectDefinition::ID->value => $message->id,
			GeometryDashMessageObjectDefinition::ACCOUNT_ID->value => $targetAccount->id,
			GeometryDashMessageObjectDefinition::PLAYER_ID->value => $targetAccount->player->id,
			GeometryDashMessageObjectDefinition::SUBJECT->value => Base64Url::encode($message->subject, true),
			GeometryDashMessageObjectDefinition::BODY->value => Base64Url::encode($this->algorithmService->xor($message->body, GeometryDashXorKeys::MESSAGE->value), true),
			GeometryDashMessageObjectDefinition::PLAYER_NAME->value => $targetAccount->player->name,
			GeometryDashMessageObjectDefinition::AGE->value => $message->created_at->diffForHumans(syntax: true),
			GeometryDashMessageObjectDefinition::IS_READ->value => $message->readed,
			GeometryDashMessageObjectDefinition::IS_SENDER->value => isset($data['getSent'])
		], GeometryDashMessageObjectDefinition::GLUE);
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