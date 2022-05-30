<?php

namespace App\Models\GDCS;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LevelDownloadRecord extends Model
{
    protected $table = 'gdcs_level_download_records';
    protected $fillable = ['level_id', 'ip', 'user_id'];
}
