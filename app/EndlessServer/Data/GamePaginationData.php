<?php

namespace App\EndlessServer\Data;

use Illuminate\Support\Collection;

readonly class GamePaginationData
{
	public function __construct(
		public Collection $items,
		public int        $total,
		public int        $page,
		public int        $perPage
	)
	{

	}

	public function info(): string
	{
		return implode(':', [
			$this->total,
			max(0, $this->page - 1) * $this->perPage,
			$this->perPage
		]);
	}
}