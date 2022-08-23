<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\AccountSettingFriendRequestState;
use App\Enums\Response;
use App\Exceptions\GDCS\GameException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountFriendRequestAcceptRequest;
use App\Http\Requests\GDCS\AccountFriendRequestDeleteRequest;
use App\Http\Requests\GDCS\AccountFriendRequestFetchRequest;
use App\Http\Requests\GDCS\AccountFriendRequestSendRequest;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountFriend;
use App\Models\GDCS\AccountFriendRequest;
use App\Services\Game\AlgorithmService;
use App\Services\Game\BaseGameService;
use App\Services\Game\ObjectService;
use Illuminate\Support\Arr;

class AccountFriendRequestController extends Controller
{
    /**
     * @throws GameException
     */
    public function send(AccountFriendRequestSendRequest $request): int
    {
        $data = $request->validated();
        $target = Account::query()
            ->find($data['targetAccountID']);

        if (!$target) {
            throw new GameException(__('error.game.account.not_found'), response_code: Response::GAME_ACCOUNT_FRIEND_REQUEST_CREATE_FAILED_NOT_FOUND->value);
        }

        $targetHasBlockedOperator = $target->blocks()
            ->where('target_account_id', $data['accountID'])
            ->exists();

        if ($targetHasBlockedOperator) {
            throw new GameException(__('error.game.account.blocked_by_target'), response_code: Response::GAME_ACCOUNT_FRIEND_REQUEST_CREATE_FAILED_BLOCKED_BY_TARGET->value);
        }

        if ($target->setting->friend_request_state === AccountSettingFriendRequestState::NONE) {
            throw new GameException(__('error.game.account.target_not_allowed_to_send_friend_request'), response_code: Response::GAME_ACCOUNT_FRIEND_REQUEST_CREATE_FAILED_TARGET_DISABLED->value);
        }

        $friendRequest = new AccountFriendRequest();
        $friendRequest->account_id = $data['accountID'];
        $friendRequest->target_account_id = $data['toAccountID'];
        $friendRequest->comment = $data['comment'];
        $friendRequest->save();

        return $friendRequest->id;
    }

    /**
     * @throws GameException
     */
    public function index(AccountFriendRequestFetchRequest $request): int|string
    {
        $data = $request->validated();
        $getSent = !empty($data['getSent']);

        if ($getSent) {
            $query = AccountFriendRequest::query()
                ->where('account_id', $data['accountID'])
                ->with('target_account.user.score');
        } else {
            $query = AccountFriendRequest::query()
                ->where('target_account_id', $data['accountID'])
                ->with('account.user.score');
        }

        $count = $query->count();
        if ($count <= 0) {
            throw new GameException(__('error.game.account.friend_request.empty'), response_code: Response::GAME_ACCOUNT_FRIEND_REQUEST_INDEX_FAILED_EMPTY->value);
        }

        return implode('#', [
            $query->forPage(++$data['page'], BaseGameService::$perPage)
                ->get()
                ->map(function (AccountFriendRequest $friendRequest) use ($getSent) {
                    /** @var Account $target */
                    $target = $friendRequest->{$getSent ? 'target_account' : 'account'};

                    return ObjectService::merge([
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
                        37 => $friendRequest->created_at
                            ?->locale('en')
                            ->diffForHumans(syntax: true),
                        41 => $friendRequest->new,
                    ], ':');
                })->join('|'),
            AlgorithmService::genPage($data['page'], $count),
        ]);
    }

    /**
     * @throws GameException
     */
    public function accept(AccountFriendRequestAcceptRequest $request): int
    {
        $data = $request->validated();

        $query = AccountFriendRequest::query()
            ->whereKey($data['requestID'])
            ->where('account_id', $data['targetAccountID'])
            ->where('target_account_id', $data['accountID']);

        if (!$query->exists()) {
            throw new GameException(__('error.game.account.friend_request.not_found'), response_code: Response::GAME_ACCOUNT_FRIEND_REQUEST_ACCEPT_FAILED_NOT_FOUND->value);
        }

        AccountFriend::create([
            'account_id' => $data['accountID'],
            'friend_account_id' => $data['targetAccountID']
        ]);

        $query->delete();
        return Response::GAME_ACCOUNT_FRIEND_REQUEST_ACCEPT_SUCCESS->value;
    }

    /**
     * @throws GameException
     */
    public function delete(AccountFriendRequestDeleteRequest $request): int
    {
        $data = $request->validated();
        $targets = !empty($data['accounts']) ? explode(',', $data['accounts']) : Arr::wrap($data['targetAccountID']);

        if (!empty($data['isSender'])) {
            $query = AccountFriendRequest::query()
                ->where('account_id', $data['accountID'])
                ->whereIn('target_account_id', $targets);
        } else {
            $query = AccountFriendRequest::query()
                ->where('target_account_id', $data['accountID'])
                ->whereIn('account_id', $targets);
        }

        if (!$query->exists()) {
            throw new GameException(__('error.game.account.friend_request.not_found'), response_code: Response::GAME_ACCOUNT_FRIEND_REQUEST_DELETE_FAILED_NOT_FOUND->value);
        }

        $query->delete();
        return Response::GAME_ACCOUNT_FRIEND_REQUEST_DELETE_SUCCESS->value;
    }
}
