<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginApiRequest;
use App\Http\Requests\UserRegisterApiRequest;
use App\Http\Requests\UserSettingUpdateApiRequest;
use App\Http\Requests\UserVerifyApiRequest;
use App\Http\Traits\HasMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    use HasMessage;

    public function register(UserRegisterApiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::query()
            ->create($data);

        Auth::login($user, true);
        $user->sendEmailVerificationNotification();
        $this->message(__('messages.register_success'), ['type' => 'success']);

        return to_route('home');
    }

    public function verify(UserVerifyApiRequest $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            $this->message(__('messages.email_already_verified'), ['type' => 'error']);
            return back();
        }

        $user->markEmailAsVerified();
        $this->message(__('messages.email_verified'), ['type' => 'success']);

        return to_route('home');
    }

    public function login(UserLoginApiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (!Auth::attempt($data, true)) {
            $this->message(__('messages.login_failed'), ['type' => 'error']);
            return back();
        }

        return Redirect::intended();
    }

    public function logout(): RedirectResponse
    {
        Auth::logoutCurrentDevice();
        return to_route('home');
    }

    public function resendEmailVerification(): RedirectResponse
    {
        $user = Request::user();
        $attempt = RateLimiter::attempt("gdcn:resendEmailVerification:$user->id", 1, function () use ($user) {
            if ($user->hasVerifiedEmail()) {
                $this->message(__('messages.email_already_verified'), ['type' => 'error']);
                return;
            }

            $user->sendEmailVerificationNotification();
            $this->message(__('messages.verification_sent'), ['type' => 'success']);
        }, 3600);

        if (!$attempt) {
            $this->message(__('messages.too_fast'), ['type' => 'error']);
        }

        return back();
    }

    public function updateSetting(UserSettingUpdateApiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = $request->user();
        $user->update($data);

        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->save();

            $user->sendEmailVerificationNotification();
        }

        $this->message(__('messages.profile_updated'), ['type' => 'success']);
        return back();
    }
}
