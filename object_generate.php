<?php

use App\GeometryDash\Enums\Objects\GeometryDashLeaderboardObjectDefinitions;
use App\GeometryDash\Services\GeometryDashObjectService;

$input = '';
$enum = GeometryDashLeaderboardObjectDefinitions::class;

$collection = collect(
	app(GeometryDashObjectService::class)->split($input, $enum::GLUE)
);

$cases = GeometryDashLeaderboardObjectDefinitions::cases();

if (
	$collection->count() === count($cases)
) {
	echo 'none';
}

if ($collection->count() < count($cases) / 2) {
	$collection->map(function ($_, $k) use ($enum) {
		return $enum::from($k);
	})->sortBy(function ($e) {
		return $e->value;
	})->map(function ($e) use ($enum) {
		return last(
				explode('\\', $enum)
			) . '::' . $e->name . '->value';
	})->join(',');
}

if ($collection->count() > count($cases) / 2) {
	collect($cases)
		->map(function ($enum) {
			return $enum->value;
		})
		->diff(
			$collection->map(function ($_, $k) use ($enum) {
				return $enum::from($k);
			})->sortBy(function ($enum) {
				return $enum->value;
			})->map(function ($enum) {
				return $enum->value;
			})
		)
		->map(function ($value) use ($enum) {
			$parts = explode('\\', $enum);
			return last($parts) . '::' . $enum::from($value)->name . '->value';
		});
}