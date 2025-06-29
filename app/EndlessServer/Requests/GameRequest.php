<?php

namespace App\EndlessServer\Requests;

use App\Api\Requests\ApiRequest;
use App\EndlessServer\Exceptions\EndlessServerGameException;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Contracts\Validation\Validator;
use Throwable;

class GameRequest extends ApiRequest
{
	/**
	 * @throws EndlessServerGameException
	 */
	protected function failedAuthorization()
	{
		throw new EndlessServerGameException(__('请求鉴权失败'), GeometryDashResponses::REQUEST_AUTHORIZATION_FAILED->value);
	}

	/**
	 * @throws EndlessServerGameException
	 */
	protected function failedValidation(Validator $validator): void
	{
		try {
			parent::failedValidation($validator);
		} catch (Throwable $e) {
			throw new EndlessServerGameException(__('请求校验失败'), GeometryDashResponses::REQUEST_VALIDATION_FAILED->value, $e);
		}
	}
}