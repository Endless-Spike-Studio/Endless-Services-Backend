<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\AccountSettingFriendRequestState;
use App\Enums\GDCS\Game\Objects\FriendRequestObject;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\AccountFriendRequestAcceptRequest;
use App\Http\Requests\GDCS\Game\AccountFriendRequestDeleteRequest;
use App\Http\Requests\GDCS\Game\AccountFriendRequestFetchRequest;
use App\Http\Requests\GDCS\Game\AccountFriendRequestSendRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountFriend;
use App\Models\GDCS\AccountFriendRequest;
use App\Services\Game\AlgorithmService;
use App\Services\Game\BaseGameService;
use App\Services\Game\ObjectService;
use Illuminate\Support\Arr;

class AccountFriendRequestController extends Controller
{
    use GameLog;

    /**
     * @throws GeometryDashChineseServerException
     */
    public function send(AccountFriendRequestSendRequest $request): int
    {
        $data = $request->validated();
        $target = Account::query()
            ->find($data['targetAccountID']);

        if (!$target) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_friend_request_send_failed_target_not_found'), game_response: Response::GAME_ACCOUNT_FRIEND_REQUEST_CREATE_FAILED_TARGET_NOT_FOUND->value);
        }

        $targetHasBlockedOperator = $target->blocks()
            ->where('target_account_id', $data['accountID'])
            ->exists();

        if ($targetHasBlockedOperator) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_friend_request_send_failed_blocked_by_target'), game_response: Response::GAME_ACCOUNT_FRIEND_REQUEST_CREATE_FAILED_BLOCKED_BY_TARGET->value);
        }

        if ($target->setting->friend_request_state === AccountSettingFriendRequestState::NONE) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_friend_request_send_failed_blocked_by_target_setting'), game_response: Response::GAME_ACCOUNT_FRIEND_REQUEST_CREATE_FAILED_TARGET_DISABLED->value);
        }

        $friendRequest = AccountFriendRequest::create([
            'account_id' => $data['accountID'],
            'target_account_id' => $data['toAccountID'],
            'comment' => $data['comment'],
        ]);

        $this->logGame(__('gdcn.game.action.account_friend_request_send_success'), [
            'friend_request_id' => $friendRequest->id
        ]);

        return $friendRequest->id;
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function index(AccountFriendRequestFetchRequest $request): string
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
            throw new GeometryDashChineseServerException(__($getSent ? 'gdcn.game.error.account_friend_request_index_failed_empty_sent' : 'gdcn.game.error.account_friend_request_index_failed_empty'), game_response: Response::GAME_ACCOUNT_FRIEND_REQUEST_INDEX_FAILED_EMPTY->value);
        }

        $this->logGame(__('gdcn.game.action.account_friend_request_index_success'));
        return implode('#', [
            $query->forPage(++$data['page'], BaseGameService::$perPage)
                ->get()
                ->map(function (AccountFriendRequest $friendRequest) use ($getSent) {
                    /** @var Account $target */
                    $target = $friendRequest->{$getSent ? 'target_account' : 'account'};

                    return ObjectService::merge([
                        FriendRequestObject::TARGET_NAME => $target->user->name,
                        FriendRequestObject::TARGET_USER_ID => $target->user->id,
                        FriendRequestObject::TARGET_ICON_ID => $target->user->score->icon,
                        FriendRequestObject::TARGET_COLOR_ID => $target->user->score->color1,
                        FriendRequestObject::TARGET_SECOND_COLOR_ID => $target->user->score->color2,
                        FriendRequestObject::TARGET_ICON_TYPE => $target->user->score->icon_type,
                        FriendRequestObject::TARGET_SPECIAL => $target->user->score->special,
                        FriendRequestObject::TARGET_UUID => $target->user->uuid,
                        FriendRequestObject::ID => $friendRequest->id,
                        FriendRequestObject::COMMENT => $friendRequest->comment,
                        FriendRequestObject::AGE => $friendRequest->created_at
                            ?->locale('en')
                            ->diffForHumans(syntax: true),
                        FriendRequestObject::IS_NEW => $friendRequest->new,
                    ], ':');
                })->join('|'),
            AlgorithmService::genPage($data['page'], $count),
        ]);
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function accept(AccountFriendRequestAcceptRequest $request): int
    {
        $data = $request->validated();

        $friendRequest = AccountFriendRequest::query()
            ->with('target_account')
            ->find($data['requestID']);

        if (!$friendRequest) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_friend_request_accept_failed_not_found'), game_response: Response::GAME_ACCOUNT_FRIEND_REQUEST_ACCEPT_FAILED_NOT_FOUND->value);
        }

        if ($friendRequest->account_id !== $data['targetAccountID']) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_friend_request_accept_failed_target_account_not_match'), game_response: Response::GAME_ACCOUNT_FRIEND_REQUEST_ACCEPT_FAILED_TARGET_ACCOUNT_NOT_MATCH->value);
        }

        if ($friendRequest->target_account->isNot($request->account)) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_friend_request_accept_failed_not_receiver'), game_response: Response::GAME_ACCOUNT_FRIEND_REQUEST_ACCEPT_FAILED_NOT_RECEIVER->value);
        }

        AccountFriend::create([
            'account_id' => $data['accountID'],
            'friend_account_id' => $data['targetAccountID']
        ]);

        $friendRequest->delete();
        $this->logGame(__('gdcn.game.action.account_friend_request_accept_success'));

        return Response::GAME_ACCOUNT_FRIEND_REQUEST_ACCEPT_SUCCESS->value;
    }

    /**
     * @throws GeometryDashChineseServerException
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
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_friend_request_delete_failed_not_found'), game_response: Response::GAME_ACCOUNT_FRIEND_REQUEST_DELETE_FAILED_NOT_FOUND->value);
        }

        $query->delete();
        $this->logGame(__('gdcn.game.action.account_friend_request_delete_success'));

        return Response::GAME_ACCOUNT_FRIEND_REQUEST_DELETE_SUCCESS->value;
    }
}
