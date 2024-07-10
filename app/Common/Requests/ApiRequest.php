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
	protected function failedValidation(Validator $validator)
	{
		throw new FailedException(
			__('request_validation_failed'),
			422,
			data: $validator->errors()
		);
	}

	/**
	 * @throws FailedException
	 */
	protected function failedAuthorization()
	{
		throw new FailedException(
			__('request_authorization_failed'),
			401
		);
	}
}