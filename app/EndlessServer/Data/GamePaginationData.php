<?php

namespace App\EndlessServer\Data;

use Illuminate\Support\Collection;

class GamePaginationData
{
	public string $info {
		get {
			return implode(':', [
				$this->total,
				max(0, $this->page) * $this->perPage,
				$this->perPage
			]);
		}
	}

	public function __construct(
		readonly Collection $items,
		readonly int        $total,
		readonly int        $page,
		readonly int        $perPage
	)
	{

	}
}