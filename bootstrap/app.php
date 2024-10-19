<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		api: __DIR__ . '/../routes/api.php'
	)
	->withBroadcasting('', [
		'prefix' => 'api'
	])
	->withMiddleware(function (Middleware $middleware) {
		$middleware->trustProxies('*');
	})
	->withExceptions()
	->create();