<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\AccountSettingMessageState;
use App\Enums\GDCS\Game\Objects\MessageObject;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\AccountMessageDeleteRequest;
use App\Http\Requests\GDCS\Game\AccountMessageDownloadRequest;
use App\Http\Requests\GDCS\Game\AccountMessageFetchRequest;
use App\Http\Requests\GDCS\Game\AccountMessageSendRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountFriend;
use App\Models\GDCS\AccountMessage;
use App\Services\Game\AlgorithmService;
use App\Services\Game\BaseGameService;
use App\Services\Game\ObjectService;
use Illuminate\Support\Arr;

class AccountMessageController extends Controller
{
    use GameLog;

    /**
     * @throws GeometryDashChineseServerException
     */
    public function send(AccountMessageSendRequest $request): int
    {
        $data = $request->validated();

        $target = Account::query()
            ->find($data['toAccountID']);

        if (!$target) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_message_send_failed_target_not_found'), gameResponse: Response::GAME_ACCOUNT_MESSAGE_CREATE_FAILED_TARGET_NOT_FOUND->value);
        }

        $targetHasBlockedOperator = $target->blocks()
            ->where('target_account_id', $data['accountID'])
            ->exists();

        if ($targetHasBlockedOperator) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_message_send_failed_blocked_by_target'), gameResponse: Response::GAME_ACCOUNT_MESSAGE_CREATE_FAILED_BLOCKED_BY_TARGET->value);
        }

        if ($target->setting->message_state === AccountSettingMessageState::NONE) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_message_send_failed_blocked_by_target_setting'), gameResponse: Response::GAME_ACCOUNT_MESSAGE_CREATE_FAILED_TARGET_DISABLED->value);
        }

        if ($target->setting->message_state === AccountSettingMessageState::FRIENDS && !AccountFriend::findBetween($target->id, $data['accountID'])->exists()) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_message_send_failed_blocked_by_target_setting_unless_friends'), gameResponse: Response::GAME_ACCOUNT_MESSAGE_CREATE_FAILED_TARGET_DISABLED_FRIENDS_ONLY->value);
        }

        $message = AccountMessage::create([
            'account_id' => $data['accountID'],
            'target_account_id' => $data['toAccountID'],
            'subject' => $data['subject'],
            'body' => $data['body'],
        ]);

        $this->logGame(__('gdcn.game.action.account_message_send_success'), [
            'message_id' => $message->id
        ]);

        return $message->id;
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function index(AccountMessageFetchRequest $request): string
    {
        $data = $request->validated();
        $getSent = !empty($data['getSent']);

        if ($getSent) {
            $query = AccountMessage::query()
                ->where('account_id', $data['accountID'])
                ->with('target_account.user.score');
        } else {
            $query = AccountMessage::query()
                ->where('target_account_id', $data['accountID'])
                ->with('account.user.score');
        }

        $count = $query->count();
        if ($count <= 0) {
            throw new GeometryDashChineseServerException(__($getSent ? 'gdcn.game.error.account_message_index_failed_empty_sent' : 'gdcn.game.error.account_message_index_failed_empty'), gameResponse: Response::GAME_ACCOUNT_MESSAGE_INDEX_FAILED_EMPTY->value);
        }

        $this->logGame(__('gdcn.game.action.account_message_index_success'));

        return implode('#', [
            $query->forPage(++$data['page'], BaseGameService::$perPage)
                ->get()
                ->map(function (AccountMessage $message) use ($getSent) {
                    /** @var Account $target */
                    $target = $message->{$getSent ? 'target_account' : 'account'};

                    return ObjectService::merge([
                        MessageObject::ID => $message->id,
                        MessageObject::ACCOUNT_ID => $target->id,
                        MessageObject::USER_ID => $target->user->id,
                        MessageObject::SUBJECT => $message->subject,
                        MessageObject::USER_NAME => $target->user->name,
                        MessageObject::AGE => $message->created_at
                            ?->locale('en')
                            ->diffForHumans(syntax: true),
                        MessageObject::IS_READ => !$message->new,
                        MessageObject::IS_SENDER => $getSent,
                    ], ':');
                })->join('|'),
            AlgorithmService::genPage($data['page'], $count),
        ]);
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function fetch(AccountMessageDownloadRequest $request): string
    {
        $data = $request->validated();
        $isSender = !empty($data['isSender']);

        $message = AccountMessage::query()
            ->find($data['messageID']);

        if (!$message) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_message_fetch_failed_not_found'), gameResponse: Response::GAME_ACCOUNT_MESSAGE_FETCH_FAILED_NOT_FOUND->value);
        }

        /** @var Account $owner */
        /** @var Account $target */

        $owner = $message->{$isSender ? 'account' : 'target_account'};
        $target = $message->{$isSender ? 'target_account' : 'account'};

        if ($owner->isNot($request->account)) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_message_fetch_failed_not_owner'), gameResponse: Response::GAME_ACCOUNT_MESSAGE_FETCH_FAILED_NOT_OWNER->value);
        }

        $new = $message->new;

        if (!$isSender) {
            $message->update([
                'new' => false
            ]);
        }

        $this->logGame(__('gdcn.game.action.account_message_fetch_success'));

        return ObjectService::merge([
            MessageObject::ID => $message->id,
            MessageObject::ACCOUNT_ID => $target->id,
            MessageObject::USER_ID => $target->user->id,
            MessageObject::SUBJECT => $message->subject,
            MessageObject::BODY => $message->body,
            MessageObject::USER_NAME => $target->user->name,
            MessageObject::AGE => $message->created_at
                ?->locale('en')
                ->diffForHumans(syntax: true),
            MessageObject::IS_READ => $new,
            MessageObject::IS_SENDER => $isSender,
        ], ':');
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function delete(AccountMessageDeleteRequest $request): int
    {
        $data = $request->validated();
        $targets = !empty($data['messages']) ? explode(',', $data['messages']) : Arr::wrap($data['messageID']);

        if (!empty($data['isSender'])) {
            $query = AccountMessage::query()
                ->where('account_id', $data['accountID'])
                ->whereIn('target_account_id', $targets);
        } else {
            $query = AccountMessage::query()
                ->where('target_account_id', $data['accountID'])
                ->whereIn('account_id', $targets);
        }

        if (!$query->exists()) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_message_delete_failed_not_found'), gameResponse: Response::GAME_ACCOUNT_MESSAGE_DELETE_FAILED_NOT_FOUND->value);
        }

        $query->delete();
        $this->logGame(__('gdcn.game.action.account_message_delete_success'));

        return Response::GAME_ACCOUNT_MESSAGE_DELETE_SUCCESS->value;
    }
}
