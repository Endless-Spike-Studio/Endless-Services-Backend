<?php

namespace App\GeometryDashServer\Entities;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
	protected $table = 'gdcs_challenges';
	protected $fillable = ['type', 'collect', 'name', 'reward'];
}
