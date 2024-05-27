<?php

namespace App\Policies\GDCS;

use App\Models\GDCS\Level;
use App\Models\GDCS\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LevelPolicy
{
	use HandlesAuthorization;

	public function edit(User $user, Level $level): bool
	{
		return $level->creator->is($user);
	}

	public function delete(User $user, Level $level): Response
	{
		if (!$level->creator->is($user)) {
			return Response::deny(__('gdcn.policy.error.not_level_owner'));
		}

		if ($level->rating->stars <> 0) {
			return Response::deny(__('gdcn.policy.error.level_rated'));
		}

		return Response::allow();
	}
}
