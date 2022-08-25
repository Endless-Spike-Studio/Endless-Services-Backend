<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\FriendState;
use App\Enums\GDCS\Game\Objects\UserObject;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Requests\GDCS\Game\AccountInfoFetchRequest;
use App\Http\Requests\GDCS\Game\AccountLoginRequest;
use App\Http\Requests\GDCS\Game\AccountModAccessRequest;
use App\Http\Requests\GDCS\Game\AccountRegisterRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountFriend;
use App\Models\GDCS\AccountFriendRequest;
use App\Models\GDCS\AccountMessage;
use App\Models\GDCS\User;
use App\Models\GDCS\UserScore;
use App\Services\Game\ObjectService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    use GameLog;

    public function register(AccountRegisterRequest $request): int
    {
        $data = $request->validated();

        $account = Account::create([
            'name' => $data['userName'],
            'password' => Hash::make($data['password']),
            'email' => $data['email']
        ]);

        $this->logGame(__('gdcn.game.action.account_register_success'));

        $account->sendEmailVerificationNotification();
        return Response::GAME_ACCOUNT_REGISTER_SUCCESS->value;
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function fetchInfo(AccountInfoFetchRequest $request): string
    {
        $data = $request->validated();

        $target = Account::query()
            ->find($data['targetAccountID']);

        if (!$target) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_profile_fetch_failed_target_not_found'), response: Response::GAME_ACCOUNT_PROFILE_FETCH_FAILED_TARGET_NOT_FOUND->value);
        }

        $userInfo = [
            UserObject::NAME => $target->user->name,
            UserObject::ID => $target->user->id,
            UserObject::STARS => $target->user->score->stars,
            UserObject::DEMONS => $target->user->score->demons,
            UserObject::CREATOR_POINTS => $target->user->score->creator_points,
            UserObject::COLOR_ID => $target->user->score->color1,
            UserObject::SECOND_COLOR_ID => $target->user->score->color2,
            UserObject::COINS => $target->user->score->coins,
            UserObject::UUID => $target->user->uuid,
            UserObject::USER_COINS => $target->user->score->user_coins,
            UserObject::MESSAGE_STATE => $target->setting->message_state->value,
            UserObject::FRIEND_REQUEST_STATE => $target->setting->friend_request_state->value,
            UserObject::YOUTUBE => $target->setting->youtube_channel,
            UserObject::CUBE_ID => $target->user->score->acc_icon,
            UserObject::SHIP_IP => $target->user->score->acc_ship,
            UserObject::BALL_ID => $target->user->score->acc_ball,
            UserObject::BIRD_ID => $target->user->score->acc_bird,
            UserObject::WAVE_ID => $target->user->score->acc_dart,
            UserObject::ROBOT_ID => $target->user->score->acc_robot,
            UserObject::GLOW_ID => $target->user->score->acc_glow,
            UserObject::IS_REGISTERED => true,
            UserObject::GLOBAL_RANK => UserScore::query()
                ->where('stars', '<=', $target->user->score->stars)
                ->count(),
            UserObject::FRIEND_STATE => FriendState::NONE->value,
            UserObject::SPIDER_ID => $target->user->score->acc_spider,
            UserObject::TWITTER => $target->setting->twitter,
            UserObject::TWITCH => $target->setting->twitch,
            UserObject::DIAMONDS => $target->user->score->diamonds,
            UserObject::EXPLOSION_ID => $target->user->score->acc_explosion,
            UserObject::MOD_LEVEL => $target->mod_level->value,
            UserObject::COMMENT_HISTORY_STATE => $target->setting->comment_history_state->value,
        ];

        if (!empty($request->account)) {
            $targetHasBlockedVisitor = $target->blocks()
                ->where('target_account_id', $data['accountID'])
                ->exists();

            if ($targetHasBlockedVisitor) {
                throw new GeometryDashChineseServerException(__('gdcn.game.error.account_profile_fetch_failed_blocked_by_target'), response: Response::GAME_ACCOUNT_PROFILE_FETCH_FAILED_BLOCKED_BY_TARGET->value);
            }

            $targetIsFriend = AccountFriend::findBetween($request->account->id, $target->id)->exists();
            if ($targetIsFriend) {
                $userInfo[UserObject::FRIEND_STATE] = FriendState::IS->value;
            }

            $outComingFriendRequestQuery = AccountFriendRequest::query()
                ->where([
                    'account_id' => $data['accountID'],
                    'target_account_id' => $target->id,
                ]);

            if ($outComingFriendRequestQuery->exists()) {
                $friendRequest = $outComingFriendRequestQuery->first();
                $userInfo[UserObject::FRIEND_STATE] = FriendState::OUT_COMING->value;
            }

            $inComingFriendRequestQuery = AccountFriendRequest::query()
                ->where([
                    'account_id' => $target->id,
                    'target_account_id' => $data['accountID'],
                ]);

            if ($inComingFriendRequestQuery->exists()) {
                $friendRequest = $inComingFriendRequestQuery->first();
                $userInfo[UserObject::FRIEND_STATE] = FriendState::IN_COMING->value;
            }

            if (!empty($friendRequest)) {
                $userInfo[UserObject::IN_COMING_FRIEND_REQUEST_ID] = $friendRequest->id;
                $userInfo[UserObject::IN_COMING_FRIEND_REQUEST_COMMENT] = $friendRequest->comment;
                $userInfo[UserObject::IN_COMING_FRIEND_REQUEST_AGE] = $friendRequest->created_at
                    ?->locale('en')
                    ->diffForHumans(syntax: true);
            }

            if ($target->is($request->account)) {
                $userInfo[UserObject::NEW_MESSAGE_COUNT] = AccountMessage::query()
                    ->where([
                        'target_account_id' => $data['targetAccountID'],
                        'new' => true,
                    ])->count();

                $userInfo[UserObject::NEW_FRIEND_REQUEST_COUNT] = AccountFriendRequest::query()
                    ->where([
                        'target_account_id' => $data['targetAccountID'],
                        'new' => true,
                    ])->count();

                $userInfo[UserObject::NEW_FRIEND_COUNT] = array_sum([
                    AccountFriend::query()
                        ->where([
                            'account_id' => $data['targetAccountID'],
                            'new' => true,
                        ])->count(),
                    AccountFriend::query()
                        ->where([
                            'friend_account_id' => $data['targetAccountID'],
                            'friend_new' => true,
                        ])->count()
                ]);
            }
        }

        $this->logGame(__('gdcn.game.action.account_profile_fetch_success'));
        return ObjectService::merge($userInfo, ':');
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function requestModAccess(AccountModAccessRequest $request): int
    {
        $modLevel = $request->account->mod_level->value;

        if ($modLevel <= 0) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_access_request_failed_not_found'), response: Response::GAME_ACCOUNT_ACCESS_REQUEST_FAILED_NOT_FOUND->value);
        }

        $this->logGame(__('gdcn.game.action.account_access_request_success'));
        return $modLevel;
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function login(AccountLoginRequest $request): string|int
    {
        $data = $request->validated();

        $account = $request->account;
        if (!$account->hasVerifiedEmail()) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_login_failed_not_verified_email'), log_context: [
                'account_id' => $account->id
            ], response: Response::GAME_ACCOUNT_LOGIN_FAILED_NEED_VERIFY_EMAIL->value);
        }

        $user = User::query()
            ->firstOrCreate([
                'uuid' => $account->id
            ], [
                'name' => $account->name,
                'udid' => $data['udid']
            ]);

        if ($user->ban->login_ban) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_login_failed_banned'), log_context: [
                'account_id' => $account->id
            ], response: Response::GAME_ACCOUNT_LOGIN_FAILED_BANNED->value);
        }

        $this->logGame(__('gdcn.game.action.account_login_success'), [
            'account_id' => $account->id,
            'user_id' => $user->id
        ]);

        return $account->id . ',' . $user->id;
    }
}
