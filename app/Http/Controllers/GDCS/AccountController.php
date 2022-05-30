<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\FriendState;
use App\Enums\GDCS\Response;
use App\Http\Requests\GDCS\AccountInfoFetchRequest;
use App\Http\Requests\GDCS\AccountLoginApiRequest;
use App\Http\Requests\GDCS\AccountModAccessRequest;
use App\Http\Requests\GDCS\AccountRegisterApiRequest;
use App\Http\Requests\GDCS\AccountSettingUpdateApiRequest;
use App\Http\Requests\GDCS\AccountVerifyRequest;
use App\Http\Requests\GDCS\Request as GDCS_Request;
use App\Http\Traits\HasMessage;
use App\Jobs\GDCS\SendEmailVerification;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountFriendRequest;
use App\Models\GDCS\User;
use App\Models\GDCS\UserScore;
use App\Services\GDCS\AccountBlockService;
use App\Services\GDCS\AccountFriendService;
use GDCN\GDObject\GDObject;
use Illuminate\Auth\SessionGuard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AccountController extends Controller
{
    use HasMessage;

    public function register(AccountRegisterApiRequest $request): int
    {
        $data = $request->validated();

        $data['name'] = $data['userName'];
        $account = Account::query()
            ->create($data);

        $account->sendEmailVerificationNotification();
        return Response::ACCOUNT_REGISTER_SUCCESS->value;
    }

    public function verify(AccountVerifyRequest $request): RedirectResponse
    {
        /** @var Account $account */
        $account = $request->user('gdcs');

        if (!$account->hasVerifiedEmail()) {
            $account->markEmailAsVerified();
            $this->message(__('messages.email_verified'), ['type' => 'success']);
        } else {
            $this->message(__('messages.email_already_verified'), ['type' => 'error']);
        }

        return to_route('gdcs.home');
    }

    public function login(AccountLoginApiRequest $request): string|int
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
            $userInfo[38] = $target->new_message_count;
            $userInfo[39] = $target->new_friend_request_count;
            $userInfo[40] = $target->new_friend_count;
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

    public function apiRegister(AccountRegisterApiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $account = Account::query()
            ->create($data);

        Auth::login($account, true);

        $account->sendEmailVerificationNotification();
        $this->message(__('messages.register_success'), ['type' => 'success']);

        return to_route('home');
    }

    public function apiLogin(GDCS_Request $gdcs_request, AccountLoginApiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $auth = Auth::guard('gdcs');

        if (!$auth->attempt($data, true)) {
            $this->message(__('messages.login_failed'), ['type' => 'error']);
            return back();
        }

        /** @var Account $account */
        $account = $auth->user();

        $gdcs_request->account = $account;
        $gdcs_request->newPlayer();

        return Redirect::intended();
    }

    public function resendEmailVerification(): RedirectResponse
    {
        $account = Request::user('gdcs');
        $attempt = RateLimiter::attempt("gdcs:resendEmailVerification:$account->id", 1, function () use ($account) {
            if ($account->hasVerifiedEmail()) {
                $this->message(__('messages.email_already_verified'), ['type' => 'error']);
            } else {
                SendEmailVerification::dispatch($account);
                $this->message(__('messages.verification_sent'), ['type' => 'success']);
            }
        }, 3600);

        if (!$attempt) {
            $this->message(__('messages.too_fast'), ['type' => 'error']);
        }

        return back();
    }

    public function updateSetting(AccountSettingUpdateApiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $auth = Auth::guard('gdcs');

        /** @var Account $account */
        $account = $auth->user();

        if ($account->email !== $data['email']) {
            $data['email_verified_at'] = null;
        }

        $account->update($data);
        SendEmailVerification::dispatch($account);

        $this->message(__('messages.profile_updated'), ['type' => 'success']);
        return back();
    }

    public function logout(): RedirectResponse
    {
        /** @var SessionGuard $auth */
        $auth = Auth::guard('gdcs');

        $auth->logoutCurrentDevice();
        return to_route('gdcs.home');
    }
}
