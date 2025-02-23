<?php

namespace App\GeometryDash\Objects;

use App\GeometryDash\Services\GeometryDashObjectService;
use BackedEnum;
use Illuminate\Support\Carbon;

abstract readonly class GameObject
{
	protected array $allowed;

	protected function __construct(
		protected string|BackedEnum $enum,
		protected string            $glue
	)
	{
		Carbon::setLocale('en');
	}

	public function only(array $allowed): GameObject
	{
		$this->allowed = $allowed;

		return $this;
	}

	public function except(array $removed): GameObject
	{
		$cases = $this->enum::cases();

		$this->allowed = collect($cases)
			->map(function (BackedEnum $enum) {
				return $enum->value;
			})
			->diff($removed)
			->toArray();

		return $this;
	}

	public function merge()
	{
		$object = [];

		$properties = $this->properties();

		foreach ($this->enum::cases() as $enum) {
			if (isset($this->allowed) && !in_array($enum->value, $this->allowed)) {
				continue;
			}

			if (isset($properties[$enum->value])) {
				$value = value($properties[$enum->value]);

				if ($value !== null) {
					$object[$enum->value] = $value;
				}
			}
		}

		return app(GeometryDashObjectService::class)->merge($object, $this->glue);
	}

	/**
	 * @return array
	 */
	abstract protected function properties(): array;
}