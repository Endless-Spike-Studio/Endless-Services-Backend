<?php

namespace App\Models\GDCS;

use App\Enums\GDCS\Response;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BannedUser extends Model
{
    protected $table = 'gdcs_banned_users';

    protected $dates = [
        'expires_at',
    ];

    public function getCommentBanInfoAttribute(): int|string|null
    {
        if (!empty($this->comment_ban)) {
            if (empty($this->expires_at)) {
                return Response::COMMENT_CREATE_FAILED_BANNED->value;
            }

            return sprintf("temp_%s_%s", $this->expires_at->diffInSeconds(), $this->reason);
        }

        return null;
    }
}
