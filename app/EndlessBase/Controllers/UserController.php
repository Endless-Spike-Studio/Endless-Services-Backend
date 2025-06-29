<?php

namespace App\EndlessBase\Controllers;

use App\Api\Responses\SuccessResponse;
use App\EndlessBase\Requests\UserLoginRequest;
use App\EndlessBase\Requests\UserRegisterRequest;
use App\EndlessBase\Services\UserService;

readonly class UserController
{
	public function __construct(
		protected UserService $service
	)
	{

	}

	public function register(UserRegisterRequest $request): SuccessResponse
	{
		$data = $request->validated();

		$this->service->register($data['name'], $data['email'], $data['password']);

		return new SuccessResponse(code: 201);
	}

	public function login(UserLoginRequest $request): SuccessResponse
	{
		$data = $request->validated();

		$result = $this->service->login($data['name'], $data['password']);

		return new SuccessResponse($result);
	}
}