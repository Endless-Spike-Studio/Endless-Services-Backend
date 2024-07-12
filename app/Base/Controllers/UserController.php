<?php

namespace App\Base\Controllers;

use App\Base\Requests\UserLoginRequest;
use App\Base\Requests\UserRegisterRequest;
use App\Base\Services\UserService;
use App\Common\Responses\SuccessResponse;

class UserController
{
	public function __construct(
		protected readonly UserService $service
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