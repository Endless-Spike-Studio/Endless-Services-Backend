<?php

namespace App\GeometryDash\Services;

use Illuminate\Support\Arr;

class GeometryDashObjectService
{
	public function merge(array $object, string $glue): string
	{
		$objects = [];
		foreach ($object as $key => $value) {
			$objects[] = implode($glue, [$key, $value]);
		}

		return implode($glue, $objects);
	}

	public function split(string $object, string $delimiter): array
	{
		$result = [];

		$objects = explode($delimiter, $object);
		for ($i = 0, $iMax = count($objects); $i < $iMax; $i += 2) {
			if (!Arr::has($objects, [$i, $i + 1])) {
				continue;
			}

			$key = $objects[$i];
			$result[$key] = $objects[$i + 1];
		}

		return $result;
	}
}