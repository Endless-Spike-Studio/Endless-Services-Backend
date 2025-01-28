<?php

namespace App\EndlessServer\Controllers;

use App\Common\Responses\FailedResponse;
use App\Common\Responses\SuccessResponse;
use App\EndlessServer\Data\EmailVerificationData;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Requests\AccountVerifyRequest;

class AccountController
{
	public function verify(AccountVerifyRequest $request): SuccessResponse|FailedResponse
	{
		$data = $request->validated();

		$verificationData = decrypt($data['_']);

		if ($verificationData instanceof EmailVerificationData) {
			$now = now();

			$expired_at = $now->subMinutes(10);

			if ($expired_at > $verificationData->created_at) {
				return new FailedResponse(
					__('验证超时')
				);
			}

			$account = Account::query()
				->where('id', $verificationData->id)
				->first();

			if (empty($account)) {
				return new FailedResponse(
					__('账号不存在')
				);
			}

			if ($account->hasVerifiedEmail()) {
				return new FailedResponse(__('账号已验证'), 202);
			}

			$account->markEmailAsVerified();

			return new SuccessResponse();
		}

		return new FailedResponse(
			__('参数错误')
		);
	}
}