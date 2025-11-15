<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Models\MapPack;
use App\EndlessServer\Objects\GameMapPackObject;
use App\EndlessServer\Requests\GameMapPackListRequest;
use App\EndlessServer\Services\GamePaginationService;
use App\GeometryDash\Enums\GeometryDashSalts;
use App\GeometryDash\Enums\Objects\GeometryDashMapPackObjectDefinition;

readonly class GameMapPackController
{
	public function __construct(
		protected GamePaginationService $paginationService
	)
	{

	}

	public function list(GameMapPackListRequest $request): string
	{
		$data = $request->validated();

		$paginate = $this->paginationService->generate(MapPack::query(), $data['page']);

		return implode(GeometryDashMapPackObjectDefinition::SEGMENTATION, [
			$paginate->items->map(function (MapPack $mapPack) {
				return new GameMapPackObject($mapPack)->merge();
			})->join(GeometryDashMapPackObjectDefinition::SEPARATOR),
			$paginate->info(),
			sha1(
				$paginate->items->map(function (MapPack $mapPack) {
					return implode('', [
						substr($mapPack->id, 0, 1),
						substr($mapPack->id, -1),
						$mapPack->stars,
						$mapPack->coins
					]);
				})->join('') .
				GeometryDashSalts::LEVEL->value
			)
		]);
	}
}