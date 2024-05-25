<?php

namespace Modules\GeometryDashProxy\Providers;

use Illuminate\Support\ServiceProvider;

class GeometryDashProxyServiceProvider extends ServiceProvider
{
	protected string $moduleName = 'GeometryDashProxy';
	protected string $moduleNameLower = 'gdproxy';

	public function boot(): void
	{
		$this->mergeConfigFrom(module_path($this->moduleName, "config/$this->moduleNameLower.php"), $this->moduleName);

		$this->loadMigrationsFrom([
			module_path($this->moduleName, 'database/migrations')
		]);
	}

	public function register(): void
	{
		$this->app->register(RouteServiceProvider::class);
	}
}