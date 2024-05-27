<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\AccountEditRequest;
use App\Http\Requests\GDCS\Web\AccountPasswordChangeRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
	use HasMessage;

	/**
	 * @throws WebException
	 */
	public function resendVerificationEmail()
	{
		$account = Auth::guard('gdcs')->user();

		if ($account->hasVerifiedEmail()) {
			throw new WebException(__('gdcn.web.error.account_verification_email_resend_failed_already_verified'));
		} else {
			$account->sendEmailVerificationNotification();
			$this->pushSuccessMessage(__('gdcn.web.action.account_verification_email_resend_success'));
		}

		return back();
	}

	public function edit(Account $account, AccountEditRequest $request)
	{
		$data = $request->validated();
		$account->update($data);

		if ($account->wasChanged('name')) {
			$account->user->update([
				'name' => $account->name
			]);
		}

		if ($account->wasChanged('email')) {
			$account->update([
				'email_verified_at' => null
			]);

			$account->sendEmailVerificationNotification();
			$this->pushSuccessMessage(__('gdcn.dashboard.action.account_email_edit_success_please_re_verify_email'));
		}

		$this->pushSuccessMessage(__('gdcn.dashboard.action.account_edit_success'));
		return back();
	}

	public function changePassword(Account $account, AccountPasswordChangeRequest $request)
	{
		$data = $request->validated();

		$account->update([
			'password' => Hash::make($data['password'])
		]);

		$this->pushSuccessMessage(__('gdcn.dashboard.action.account_password_change_success'));
		return back();
	}
}
