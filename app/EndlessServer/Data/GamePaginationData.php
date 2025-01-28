<?php

namespace App\EndlessServer\Data;

use Illuminate\Support\Collection;

class GamePaginationData
{
	public string $info {
		get {
			return implode(':', [
				$this->total,
				$this->page,
				$this->perPage
			]);
		}
	}

	public function __construct(
		public Collection $items,

		public int        $total,
		public int        $page,
		public int        $perPage
	)
	{

	}


}