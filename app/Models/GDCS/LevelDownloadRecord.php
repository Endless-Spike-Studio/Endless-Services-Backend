<?php

namespace App\Models\GDCS;

use Illuminate\Database\Eloquent\Model;

class LevelDownloadRecord extends Model
{
    protected $table = 'gdcs_level_download_records';

    protected $fillable = ['level_id', 'ip', 'user_id'];
}
