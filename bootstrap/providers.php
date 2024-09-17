<?php

use App\Common\Providers\EndlessServiceProvider;
use App\EndlessServer\Providers\EndlessServerGuardServiceProvider;

return [
	EndlessServiceProvider::class,
	EndlessServerGuardServiceProvider::class
];