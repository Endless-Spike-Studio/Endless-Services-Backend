<?php

namespace App\GeometryDash\Services;

use App\GeometryDash\Enums\GeometryDashSalts;

class GeometryDashAlgorithmService
{
	public function generateGjp2(string $password): string
	{
		return sha1($password . GeometryDashSalts::GJP2->value);
	}
}