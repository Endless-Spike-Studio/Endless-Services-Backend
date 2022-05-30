<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountFriendRequestAcceptRequest;
use App\Http\Requests\GDCS\AccountFriendRequestDeleteRequest;
use App\Http\Requests\GDCS\AccountFriendRequestFetchRequest;
use App\Http\Requests\GDCS\AccountFriendRequestSendRequest;
use App\Models\GDCS\AccountFriend;
use App\Models\GDCS\AccountFriendRequest;
use GDCN\GDAlgorithm\GDAlgorithm;
use GDCN\GDObject\GDObject;
use Illuminate\Support\Arr;

class AccountFriendRequestController extends Controller
{
    public function send(AccountFriendRequestSendRequest $request): int
    {
        $data = $request->validated();

        if ($request->account->isBlock($data['toAccountID'])) {
            return Response::ACCOUNT_FRIEND_REQUEST_SEND_FAILED_TARGET_BLOCKED->value;
        }

        $model = new AccountFriendRequest();
        $model->account_id = $data['accountID'];
        $model->target_account_id = $data['toAccountID'];
        $model->comment = $data['comment'];
        $model->save();

        return $model->id;
    }

    public function fetchAll(AccountFriendRequestFetchRequest $request): int|string
    {
        $data = $request->validated();
        $perPage = config('gdcs.perPage', 10);

        $query = AccountFriendRequest::query()
            ->where(!empty($data['getSent']) ? 'account_id' : 'target_account_id', $data['accountID']);

        $count = $query->count();
        if ($count <= 0) {
            return Response::ACCOUNT_FRIEND_REQUEST_EMPTY->value;
        }

        return implode('#', [
            $query->forPage(++$data['page'], $perPage)
                ->with(!empty($data['getSent']) ? 'target_account.user.score' : 'account.user.score')
                ->get()
                ->map(function (AccountFriendRequest $friendRequest) use ($data) {
                    if (!$target = !empty($data['getSent']) ? $friendRequest->target_account : $friendRequest->account) {
                        return null;
                    }

                    return GDObject::merge([
                        1 => $target->name,
                        2 => $target->user->id,
                        9 => $target->user->score->icon,
                        10 => $target->user->score->color1,
                        11 => $target->user->score->color2,
                        14 => $target->user->score->icon_type,
                        15 => $target->user->score->acc_glow,
                        16 => $target->id,
                        32 => $friendRequest->id,
                        35 => $friendRequest->comment,
                        37 => $friendRequest->created_at?->locale('en')->diffForHumans(syntax: true),
                        41 => $friendRequest->new
                    ], ':');
                })->join('|'),
            GDAlgorithm::genPage($data['page'], $query->count(), $perPage)
        ]);
    }

    public function delete(AccountFriendRequestDeleteRequest $request): int
    {
        $data = $request->validated();

        $accounts = !empty($data['accounts']) ? explode(',', $data['accounts']) : Arr::wrap($data['targetAccountID']);
        return AccountFriendRequest::query()
            ->where(!empty($data['isSender']) ? 'account_id' : 'target_account_id', $data['accountID'])
            ->whereIn(!empty($data['isSender']) ? 'target_account_id' : 'account_id', $accounts)
            ->delete()
            ? Response::ACCOUNT_FRIEND_REQUEST_DELETE_SUCCESS->value
            : Response::ACCOUNT_FRIEND_REQUEST_DELETE_FAILED->value;
    }

    public function accept(AccountFriendRequestAcceptRequest $request): int
    {
        $data = $request->validated();

        $query = AccountFriendRequest::query()
            ->whereKey($data['requestID'])
            ->where('account_id', $data['targetAccountID'])
            ->where('target_account_id', $data['accountID']);

        $friend = new AccountFriend();
        $friend->account_id = $data['accountID'];
        $friend->friend_account_id = $data['targetAccountID'];
        $friend->save();

        return $query->delete()
            ? Response::ACCOUNT_FRIEND_REQUEST_ACCEPT_SUCCESS->value
            : Response::ACCOUNT_FRIEND_REQUEST_ACCEPT_FAILED->value;
    }
}
