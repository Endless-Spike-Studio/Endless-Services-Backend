<?php

namespace App\Policies\GDCS;

use App\Models\GDCS\Level;
use App\Models\GDCS\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LevelPolicy
{
    use HandlesAuthorization;

    public function edit(User $user, Level $level): bool
    {
        return $level->creator->is($user);
    }
}
