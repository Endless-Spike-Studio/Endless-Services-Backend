<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\LoginRequest;
use App\Http\Traits\HasMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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

    public function logout()
    {
        Auth::guard('gdcs')->logout();
        $this->pushSuccessMessage(__('gdcn.web.action.logout_success'));
        return back();
    }
}
