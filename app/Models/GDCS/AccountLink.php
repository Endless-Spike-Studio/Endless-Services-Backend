<?php

namespace App\Models\GDCS;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AccountLink extends Model
{
    protected $table = 'gdcs_account_links';
    protected $fillable = ['server', 'target_name', 'target_account_id', 'target_user_id'];
}
