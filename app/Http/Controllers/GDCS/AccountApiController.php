<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AbilityUpdateApiRequest;
use App\Http\Requests\GDCS\AccountLoginApiRequest;
use App\Http\Requests\GDCS\AccountPermissionUpdateApiRequest;
use App\Http\Requests\GDCS\AccountRegisterApiRequest;
use App\Http\Requests\GDCS\AccountSettingUpdateApiRequest;
use App\Http\Requests\GDCS\AccountVerifyRequest;
use App\Http\Requests\GDCS\Request as GDCS_Request;
use App\Http\Requests\GDCS\RoleUpdateApiRequest;
use App\Http\Traits\HasMessage;
use App\Jobs\GDCS\SendEmailVerification;
use App\Models\GDCS\Account;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;

class AccountApiController extends Controller
{
    use HasMessage;

    public function updatePermission(Account $account, AccountPermissionUpdateApiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $account->disallow(
            $account->getAbilities()
        );

        foreach ($data['abilities'] as $ability) {
            BouncerFacade::allow($account)->to($ability);
        }

        $account->retract(
            $account->getRoles()
        );

        foreach ($data['roles'] as $role) {
            BouncerFacade::assign($role)->to($account);
        }

        $this->pushSuccessMessage(
            __('messages.update_success')
        );

        return back();
    }

    public function updateRole(Role $role, RoleUpdateApiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $role->update($data);

        $this->pushSuccessMessage(
            __('messages.update_success')
        );

        return back();
    }

    public function updateAbility(Ability $ability, AbilityUpdateApiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $ability->update($data);

        $this->pushSuccessMessage(
            __('messages.update_success')
        );

        return back();
    }

    public function register(AccountRegisterApiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $account = Account::create($data);
        Auth::login($account, true);

        $account->update([
            'password' => Hash::make($data['password'])
        ]);

        $this->pushSuccessMessage(
            __('messages.register_success')
        );

        $account->sendEmailVerificationNotification();
        return to_route('home');
    }

    public function login(GDCS_Request $gdcs_request, AccountLoginApiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $auth = Auth::guard('gdcs');

        if (!$auth->attempt($data, true)) {
            $this->pushErrorMessage(
                __('messages.login_failed')
            );

            return back();
        }

        /** @var Account $account */
        $account = $auth->user();

        $gdcs_request->account = $account;
        $gdcs_request->newPlayer();

        $this->pushSuccessMessage(
            __('messages.welcome_back', [
                'name' => $account->name,
            ])
        );

        return Redirect::intended();
    }

    public function resendEmailVerification(): RedirectResponse
    {
        $account = Request::user('gdcs');

        $attempt = RateLimiter::attempt(
            "gdcs:resendEmailVerification:$account->id",
            1,
            function () use ($account) {
                if ($account->hasVerifiedEmail()) {
                    $this->pushErrorMessage(
                        __('messages.email_already_verified')
                    );
                } else {
                    SendEmailVerification::dispatch($account);

                    $this->pushSuccessMessage(
                        __('messages.verification_sent')
                    );
                }
            }, 3600);

        if (!$attempt) {
            $this->pushErrorMessage(
                __('messages.too_fast')
            );
        }

        return back();
    }

    public function logout(): RedirectResponse
    {
        /** @var SessionGuard $auth */
        $auth = Auth::guard('gdcs');
        $auth->logoutCurrentDevice();

        $this->pushSuccessMessage(
            __('messages.logout_success')
        );

        return to_route('gdcs.home');
    }

    public function verify(AccountVerifyRequest $request): RedirectResponse
    {
        /** @var Account $account */
        $account = $request->user('gdcs');

        if (!$account->hasVerifiedEmail()) {
            $account->markEmailAsVerified();

            $this->pushSuccessMessage(
                __('messages.email_verified')
            );
        } else {
            $this->pushErrorMessage(
                __('messages.email_already_verified')
            );
        }

        return to_route('gdcs.home');
    }

    public function updateSetting(AccountSettingUpdateApiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $auth = Auth::guard('gdcs');

        /** @var Account $account */
        $account = $auth->user();
        $account->update($data);

        if ($account->wasChanged('password')) {
            $account->update([
                'password' => $data['password']
            ]);
        }

        if ($account->wasChanged('email')) {
            $account->update([
                'email_verified_at' => null,
            ]);

            $account->sendEmailVerificationNotification();
        }

        $this->pushSuccessMessage(
            __('messages.profile_updated')
        );

        return back();
    }
}
