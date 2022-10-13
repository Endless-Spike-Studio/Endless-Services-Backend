<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Exceptions\GDCS\WebException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\LoginRequest;
use App\Http\Traits\HasMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    use HasMessage;

    /**
     * @throws WebException
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (!Auth::guard('gdcs')->attempt($data)) {
            throw new WebException(__('gdcn.web.error.login_failed'));
        }

        $this->pushSuccessMessage(__('gdcn.web.action.login_success'));
        return Redirect::away(Session::pull('back_url') ?? '/');
    }

    public function logout()
    {
        Auth::guard('gdcs')->logout();
        $this->pushSuccessMessage(__('gdcn.web.action.logout_success'));
        return back();
    }
}
