<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

try {
	$containerBuilder = new ContainerBuilder();
	$container = $containerBuilder->build();

	$app = AppFactory::create($container);

	foreach (scandir($base = __DIR__) as $name) {
		if (
			$name === '.' || $name === '..' ||
			!is_dir($path = $base . '/' . $name) ||
			!file_exists($file = $path . '/index.php')
		) {
			continue;
		}

		(require_once $file)($app);
	}

	$app->run();
} catch (Throwable) {

}