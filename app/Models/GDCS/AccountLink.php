<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Model;

class AccountLink extends Model
{
    protected $table = 'gdcs_account_links';

    protected $fillable = ['server', 'target_name', 'target_account_id', 'target_user_id'];
}
