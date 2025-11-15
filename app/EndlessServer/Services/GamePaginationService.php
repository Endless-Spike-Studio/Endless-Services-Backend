<?php

namespace App\EndlessServer\Services;

use App\EndlessServer\Data\GamePaginationData;
use Illuminate\Contracts\Database\Query\Builder;

readonly class GamePaginationService
{
	public function generate(Builder $query, int $page, ?int $perPage = null)
	{
		if ($perPage === null) {
			$perPage = config('services.endless_server.per_page');
		}

		return app(GamePaginationData::class, [
			'items' => $query->forPage($page, $perPage)->get(),

			'total' => tap($query->toBase(), function (Builder $query) {
				$query->groups = null;
				$query->orders = null;
			})->count(),
			'page' => $page,
			'perPage' => $perPage
		]);
	}
}