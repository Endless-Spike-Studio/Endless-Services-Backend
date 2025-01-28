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
		throw new FailedException(__('endless_services.request.authorization.failed'), 401);
	}

	/**
	 * @throws FailedException
	 */
	protected function failedValidation(Validator $validator)
	{
		throw new FailedException(
			__('endless_services.request.validation.failed'),
			422,
			data: $validator->errors()
		);
	}
}