<?php

namespace App\Common\Requests;

use App\Common\Exceptions\FailedException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
	/**
	 * @throws FailedException
	 */
	protected function failedAuthorization()
	{
		throw new FailedException(__('请求鉴权失败'), 401);
	}

	/**
	 * @throws FailedException
	 */
	protected function failedValidation(Validator $validator)
	{
		throw new FailedException(
			__('请求校验失败'),
			422,
			data: $validator->errors()
		);
	}
}