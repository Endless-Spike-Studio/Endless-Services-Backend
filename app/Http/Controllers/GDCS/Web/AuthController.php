<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\LoginRequest;
use App\Http\Traits\HasMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    use HasMessage;

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (Auth::guard('gdcs')->attempt($data)) {
            $this->pushSuccessMessage(__('gdcn.web.action.login_success'));
        } else {
            $this->pushErrorMessage(__('gdcn.web.error.login_failed'));
        }

        return Redirect::away(Session::pull('back_url') ?? '/');
    }

    public function logout()
    {
        Auth::guard('gdcs')->logout();
        $this->pushSuccessMessage(__('gdcn.web.action.logout_success'));
        return back();
    }
}
