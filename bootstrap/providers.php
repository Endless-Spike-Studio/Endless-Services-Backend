<?php

use App\Common\Providers\BlueprintMacroServiceProvider;
use App\EndlessServer\Providers\EndlessServerGuardServiceProvider;
use App\EndlessServices\Providers\EndlessGuardServiceProvider;

return [
	EndlessServerGuardServiceProvider::class,
	BlueprintMacroServiceProvider::class,
	EndlessGuardServiceProvider::class
];