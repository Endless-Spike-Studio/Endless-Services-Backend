<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Events\AccountRegistered;
use App\Exceptions\GDCS\WebException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\LoginRequest;
use App\Http\Requests\GDCS\Web\RegisterRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    use HasMessage;

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $account = Account::create([
            'name' => $data['userName'],
            'password' => Hash::make($data['password']),
            'email' => $data['email']
        ]);

        event(new AccountRegistered($account));
        Auth::login($account);

        return Redirect::intended();
    }

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
