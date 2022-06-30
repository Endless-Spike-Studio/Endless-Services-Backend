<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\FriendState;
use App\Enums\GDCS\Response;
use App\Events\GDCS\AccountRegistered;
use App\Http\Requests\GDCS\AccountInfoFetchRequest;
use App\Http\Requests\GDCS\AccountLoginRequest;
use App\Http\Requests\GDCS\AccountModAccessRequest;
use App\Http\Requests\GDCS\AccountRegisterRequest;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountFriendRequest;
use App\Models\GDCS\User;
use App\Models\GDCS\UserScore;
use App\Repositories\GDCS\AccountFriendRepository;
use App\Repositories\GDCS\AccountFriendRequestRepository;
use App\Repositories\GDCS\AccountMessageRepository;
use App\Services\GDCS\AccountBlockService;
use App\Services\GDCS\AccountFriendService;
use GDCN\GDObject\GDObject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;

class AccountController extends Controller
{
    public function register(AccountRegisterRequest $request): int
    {
        $data = Arr::rename($request->validated(), [
            'userName' => 'name'
        ]);

        $account = Account::create($data);
        AccountRegistered::dispatch($account);

        return Response::ACCOUNT_REGISTER_SUCCESS->value;
    }

    /**
     * @throws ModelNotFoundException
     */
    public function fetchInfo(
        AccountInfoFetchRequest $request,
        AccountBlockService     $block,
        AccountFriendService    $friend
    ): int|string
    {
        $data = $request->validated();

        $target = Account::query()
            ->findOrFail($data['targetAccountID']);

        $friendState = FriendState::NONE->value;
        $requestAuth = $request->auth();

        if ($requestAuth) {
            if ($block->check($target->id, $data['accountID'])) {
                return Response::ACCOUNT_INFO_FETCH_FAILED_BLOCKED->value;
            }

            if ($friend->check($target->id, $data['accountID'])) {
                $friendState = FriendState::IS->value;
            }

            $query = AccountFriendRequest::query()
                ->where([
                    'account_id' => $data['accountID'],
                    'target_account_id' => $target->id
                ]);

            if ($query->exists()) {
                $friendRequest = $query->first();
                $friendState = FriendState::OUT_COMING->value;
            }

            $query = AccountFriendRequest::query()
                ->where([
                    'account_id' => $target->id,
                    'target_account_id' => $data['accountID']
                ]);

            if ($query->exists()) {
                $friendRequest = $query->first();
                $friendState = FriendState::IN_COMING->value;
            }
        }

        $userInfo = [
            1 => $target->name,
            2 => $target->user->id,
            3 => $target->user->score->stars,
            4 => $target->user->score->demons,
            8 => $target->user->score->creator_points,
            10 => $target->user->score->color1,
            11 => $target->user->score->color2,
            13 => $target->user->score->coins,
            16 => $target->id,
            17 => $target->user->score->user_coins,
            18 => $target->setting->message_state->value,
            19 => $target->setting->friend_request_state->value,
            20 => $target->setting->youtube_channel,
            21 => $target->user->score->acc_icon,
            22 => $target->user->score->acc_ship,
            23 => $target->user->score->acc_ball,
            24 => $target->user->score->acc_bird,
            25 => $target->user->score->acc_dart,
            26 => $target->user->score->acc_robot,
            28 => $target->user->score->acc_glow,
            29 => !empty($target),
            30 => UserScore::query()
                ->where('stars', '<=', $target->user->score->stars)
                ->count(),
            31 => $friendState,
            43 => $target->user->score->acc_spider,
            44 => $target->setting->twitter,
            45 => $target->setting->twitch,
            46 => $target->user->score->diamonds,
            48 => $target->user->score->acc_explosion,
            49 => $target->mod_level->value,
            50 => $target->setting->comment_history_state->value
        ];

        if ($requestAuth && $data['accountID'] === $data['targetAccountID']) {
            $userInfo[38] = app(AccountMessageRepository::class)
                ->findNewByAccount($data['targetAccountID'])
                ->count();

            $userInfo[39] = app(AccountFriendRequestRepository::class)
                ->findNewByAccount($data['targetAccountID'])
                ->count();

            $userInfo[40] = app(AccountFriendRepository::class)
                ->findNewByAccount($data['targetAccountID'])
                ->count();
        }

        if (!empty($friendRequest)) {
            $userInfo[32] = $friendRequest->id;
            $userInfo[35] = $friendRequest->comment;
            $userInfo[37] = $friendRequest->created_at
                ?->locale('en')
                ->diffForHumans(syntax: true);
        }

        return GDObject::merge($userInfo, ':');
    }

    public function requestModAccess(AccountModAccessRequest $request): int
    {
        $modLevel = $request->account->mod_level->value;

        if ($modLevel <= 0) {
            return Response::MOD_ACCESS_NOT_FOUND->value;
        }

        return $modLevel;
    }

    public function login(AccountLoginRequest $request): string|int
    {
        $data = $request->validated();

        $account = $request->account;
        if (!$account->hasVerifiedEmail()) {
            return Response::ACCOUNT_LOGIN_FAILED_EMAIL_NOT_VERIFIED->value;
        }

        $user = User::query()
            ->where('uuid', $account->id)
            ->firstOrNew();

        $user->name = $account->name;
        $user->uuid = $account->id;
        $user->udid = $data['udid'];
        $user->save();

        if ($user->ban?->login_ban) {
            return Response::ACCOUNT_LOGIN_FAILED_BANNED->value;
        }

        return implode(',', [
            $account->id,
            $user->id
        ]);
    }
}
