<?php

namespace App\Api\Requests;

use App\Api\Exceptions\ApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
	/**
	 * @throws ApiException
	 */
	protected function failedAuthorization()
	{
		throw new ApiException(__('请求鉴权失败'), 401);
	}

	/**
	 * @throws ApiException
	 */
	protected function failedValidation(Validator $validator)
	{
		throw new ApiException(
			__('请求校验失败'),
			422,
			data: $validator->errors()
		);
	}
}