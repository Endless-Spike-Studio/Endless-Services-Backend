<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Exceptions\EndlessServerGameException;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class GameRequest extends FormRequest
{
	/**
	 * @throws EndlessServerGameException
	 */
	protected function failedAuthorization()
	{
		throw new EndlessServerGameException(__('endless_services.request.authorization_failed'), GeometryDashResponses::FAILED->value);
	}

	/**
	 * @throws EndlessServerGameException
	 */
	protected function failedValidation(Validator $validator)
	{
		throw new EndlessServerGameException(__('endless_services.request.validation_failed'), GeometryDashResponses::FAILED->value);
	}
}