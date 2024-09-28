<?php

use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		api: __DIR__ . '/../routes/api.php'
	)
	->withBroadcasting('', [
		'prefix' => 'api'
	])
	->withMiddleware()
	->withExceptions()
	->create();