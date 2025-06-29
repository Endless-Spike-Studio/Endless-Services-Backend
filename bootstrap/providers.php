<?php

use App\Common\Providers\BlueprintMacroServiceProvider;
use App\EndlessBase\Providers\EndlessGuardServiceProvider;
use App\EndlessProxyStatistic\Providers\EndlessProxyStatisticServiceProvider;
use App\EndlessServer\Providers\EndlessServerGuardServiceProvider;

return [
	BlueprintMacroServiceProvider::class,
	EndlessGuardServiceProvider::class,
	EndlessProxyStatisticServiceProvider::class,
	EndlessServerGuardServiceProvider::class,
];