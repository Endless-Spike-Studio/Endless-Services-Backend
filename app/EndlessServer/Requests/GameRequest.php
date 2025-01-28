<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Exceptions\EndlessServerGameException;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Throwable;

class GameRequest extends FormRequest
{
	/**
	 * @throws EndlessServerGameException
	 */
	protected function failedAuthorization()
	{
		throw new EndlessServerGameException(__('endless_services.request.authorization.failed'), GeometryDashResponses::FAILED->value);
	}

	/**
	 * @throws EndlessServerGameException
	 */
	protected function failedValidation(Validator $validator): void
	{
		try {
			parent::failedValidation($validator);
		} catch (Throwable $e) {
			throw new EndlessServerGameException(__('endless_services.request.validation.failed'), GeometryDashResponses::FAILED->value, $e);
		}
	}
}