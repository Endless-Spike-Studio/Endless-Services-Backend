<?php

namespace App\GeometryDash\Services;

use App\GeometryDash\Exceptions\ResponseException;
use Illuminate\Support\Str;

class ResponseService
{
	/**
	 * @throws ResponseException
	 */
	public static function validate(string $data): void
	{
		if (!static::check($data)) {
			throw new ResponseException(
				__('[响应] 数据无效')
			);
		}
	}

	public static function check(string $data): bool
	{
		return !(
			empty($data)
			|| Str::startsWith($data, 'error')
			|| Str::startsWith($data, '<')
			|| (is_numeric($data) && $data <= 0)
		);
	}
}