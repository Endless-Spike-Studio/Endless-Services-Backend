<?php

namespace App\EndlessServer\Controllers;

use Illuminate\Support\Facades\URL;

class GameCustomContentController
{
	public function getURL(): string
	{
		return URL::action([__CLASS__, 'handle'], '/');
	}

	public function handle(string $path)
	{

	}
}
