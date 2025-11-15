<?php

namespace App\EndlessServer\Data;

use Carbon\Carbon;

readonly class EmailVerificationData
{
	public function __construct(
		public int    $id,
		public Carbon $created_at
	)
	{

	}
}