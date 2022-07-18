<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\AccountSettingMessageState;
use App\Enums\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountMessageDeleteRequest;
use App\Http\Requests\GDCS\AccountMessageDownloadRequest;
use App\Http\Requests\GDCS\AccountMessageFetchRequest;
use App\Http\Requests\GDCS\AccountMessageSendRequest;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountFriend;
use App\Models\GDCS\AccountMessage;
use GeometryDashChinese\GeometryDashAlgorithm;
use GeometryDashChinese\GeometryDashObject;
use Illuminate\Support\Arr;

class AccountMessageController extends Controller
{
    public function send(AccountMessageSendRequest $request): int
    {
        $data = $request->validated();

        $target = Account::query()
            ->find($data['toAccountID']);

        if (!$target) {
            return Response::MESSAGE_UPDATE_FAILED_TARGET_NOT_FOUND->value;
        }

        if ($request->account->isBlock($data['toAccountID'])) {
            return Response::MESSAGE_UPDATE_FAILED_TARGET_BLOCKED->value;
        }

        if ($target->setting?->message_state === AccountSettingMessageState::FRIENDS && !AccountFriend::findBetween($request->account->id, $target->id)->exists()) {
            return Response::MESSAGE_UPDATE_FAILED_TARGET_BLOCKED->value;
        }

        if ($target->setting?->message_state === AccountSettingMessageState::NONE) {
            return Response::MESSAGE_UPDATE_FAILED_TARGET_BLOCKED->value;
        }

        $message = new AccountMessage();
        $message->account_id = $data['accountID'];
        $message->target_account_id = $data['toAccountID'];
        $message->subject = $data['subject'];
        $message->body = $data['body'];
        $message->save();

        return $message->id;
    }

    public function fetchAll(AccountMessageFetchRequest $request): int|string
    {
        $data = $request->validated();
        $perPage = config('gdcs.perPage', 10);
        $isSender = !empty($data['getSent']);

        $query = AccountMessage::query()
            ->with(['account', 'target_account'])
            ->where($isSender ? 'account_id' : 'target_account_id', $data['accountID']);

        $count = $query->count();
        if ($count <= 0) {
            return Response::MESSAGE_FETCH_FAILED_EMPTY->value;
        }

        return implode('#', [
            $query->forPage(++$data['page'], $perPage)
                ->get()
                ->map(function (AccountMessage $message) use ($isSender) {
                    $account = $isSender ? $message->target_account : $message->account;
                    if ($account === null) {
                        return [];
                    }

                    return GeometryDashObject::merge([
                        1 => $message->id,
                        2 => $account->id,
                        3 => $account->user->id,
                        4 => $message->subject,
                        6 => $account->name,
                        7 => $message->created_at?->locale('en')->diffForHumans(syntax: true),
                        8 => !$message->new,
                        9 => $isSender,
                    ], ':');
                })->join('|'),
            GeometryDashAlgorithm::genPage($data['page'], $count, $perPage),
        ]);
    }

    public function fetch(AccountMessageDownloadRequest $request): int|string
    {
        $data = $request->validated();
        $isSender = !empty($data['isSender']);

        $message = AccountMessage::query()
            ->whereKey($data['messageID'])
            ->where($isSender ? 'account_id' : 'target_account_id', $data['accountID'])
            ->first();

        if (empty($message)) {
            return Response::MESSAGE_DOWNLOAD_FAILED_MESSAGE_NOT_FOUND->value;
        }

        $account = $isSender ? $message->target_account : $message->account;
        $new = $message->new;

        if (!$isSender) {
            $message->new = false;
            $message->save();
        }

        return GeometryDashObject::merge([
            1 => $message->id,
            2 => $account->id,
            3 => $account->user->id,
            4 => $message->subject,
            5 => $message->body,
            6 => $account->name,
            7 => $message->created_at?->locale('en')->diffForHumans(syntax: true),
            8 => !$new,
            9 => $isSender,
        ], ':');
    }

    public function delete(AccountMessageDeleteRequest $request): int
    {
        $data = $request->validated();
        $isSender = !empty($data['isSender']);

        $messages = !empty($data['messages']) ? explode(',', $data['messages']) : Arr::wrap($data['messageID']);

        return AccountMessage::query()
            ->whereKey($messages)
            ->where($isSender ? 'account_id' : 'target_account_id', $data['accountID'])
            ->delete()
            ? Response::MESSAGE_DELETE_SUCCESS->value
            : Response::MESSAGE_DELETE_FAILED_MESSAGE_NOT_FOUND->value;
    }
}
