<?php

namespace App\Common\Providers;

use BackedEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class BlueprintMacroServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		Blueprint::macro('forEnum', function ($column, string|BackedEnum $enum) {
			$values = [];

			foreach ($enum::cases() as $case) {
				$values[] = $case->value;
			}

			return $this->enum($column, $values);
		});
	}
}