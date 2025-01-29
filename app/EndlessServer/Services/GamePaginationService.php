<?php

namespace App\EndlessServer\Services;

use App\EndlessServer\Data\GamePaginationData;
use Illuminate\Contracts\Database\Query\Builder;

readonly class GamePaginationService
{
	public function generate(Builder $query, int $page, ?int $perPage = null)
	{
		if ($perPage === null) {
			$perPage = config('services.endless.server.per_page');
		}

		return app(GamePaginationData::class, [
			'items' => $query->get(),

			'total' => $query->count(),
			'page' => $page,
			'perPage' => $perPage
		]);
	}
}