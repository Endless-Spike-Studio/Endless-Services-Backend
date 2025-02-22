<?php

use App\Common\Providers\BlueprintMacroServiceProvider;
use App\EndlessServer\Providers\EndlessServerGuardServiceProvider;

return [
	EndlessServerGuardServiceProvider::class,
	BlueprintMacroServiceProvider::class
];