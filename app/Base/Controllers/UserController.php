<?php

namespace App\Base\Controllers;

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

		return new SuccessResponse(201);
	}
}