<?php

namespace App\Policies\GDCS;

use App\Enums\GDCS\Game\ContestRule;
use App\Models\GDCS\Account;
use App\Models\GDCS\Contest;
use App\Models\GDCS\ContestParticipant;
use App\Models\GDCS\Level;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContestPolicy
{
	use HandlesAuthorization;

	public function submit(Account $account, Contest $contest, Level $level = null): bool
	{
		$rules = $contest->rules();

		if ($rules->contains(ContestRule::EDITABLE->value)) {
			return true;
		}

		if ($rules->contains(ContestRule::UNIQUE_ACCOUNT->value)) {
			$query = $contest->participants()
				->where('account_id', $account->id);

			if ($query->exists()) {
				return false;
			}
		}

		if ($rules->contains(ContestRule::UNIQUE_LEVEL->value) && !empty($level)) {
			$query = $contest->participants()
				->where('level_id', $level->id);

			if ($query->exists()) {
				return false;
			}
		}

		if ($rules->contains(ContestRule::UNIQUE_LEVEL_CONTEST->value) && !empty($level)) {
			$query = ContestParticipant::query()
				->whereNot('contest_id', $contest->id)
				->where('level_id', $level->id);

			if ($query->exists()) {
				return false;
			}
		}

		return true;
	}
}
