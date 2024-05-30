<?php

use Illuminate\Foundation\Application;

return Application::configure(
	basePath: dirname(__DIR__)
)
	->withRouting(
		api: __DIR__ . '/../routes/api.php'
	)
	->withMiddleware()
	->withExceptions()
	->create();