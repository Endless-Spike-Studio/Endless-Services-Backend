<?php

namespace App\Policies\GDCS;

use App\Enums\GDCS\ModLevel;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountComment;
use App\Models\GDCS\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AccountCommentPolicy
{
    use HandlesAuthorization;

    public function create(User $user): Response
    {
        if ($user->ban->comment_ban) {
            if (!empty($user->ban->expires_at) && !empty($user->ban->reason)) {
                return Response::deny($user->ban->reason);
            }

            return Response::deny($user->ban->reason);
        }

        return Response::allow();
    }

    public function delete(Account $account, AccountComment $comment): bool
    {
        return ModLevel::ELDER === $account->mod_level || $account->id === $comment->account_id;
    }
}
