<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Requests\GameItemRestoreRequest;
use App\GeometryDash\Enums\GeometryDashResponses;

class GameItemController
{
	public function restore(GameItemRestoreRequest $request): int
	{
		$request->validated();

		return GeometryDashResponses::ITEM_RESTORE_SUCCESS->value;
	}
}
