<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\LoginRequest;
use App\Http\Traits\HasMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    use HasMessage;

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (!Auth::guard('gdcs')->attempt($data)) {
            $this->pushErrorMessage(__('gdcn.web.error.login_failed'));
            return back();
        }

        $this->pushSuccessMessage(__('gdcn.web.action.login_success'));
        return Redirect::intended();
    }

    public function verify()
    {
        $account = Auth::guard('gdcs')->user();
        $payload = Request::route('_');

        if (!empty($account) && !empty($payload)) {
            $purePayload = Crypt::decryptString($payload);
            [$id, $email] = explode('|', $purePayload);

            if ($account->email === $email && (string)$account->id === $id) {
                $account->markEmailAsVerified();
                $this->pushSuccessMessage(__('gdcn.web.action.account_verify_success'));
            }
        }

        return to_route('gdcs.home');
    }

    public function logout()
    {
        Auth::guard('gdcs')->logout();
        $this->pushSuccessMessage(__('gdcn.web.action.logout_success'));
        return back();
    }
}
