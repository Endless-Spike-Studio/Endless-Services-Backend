<?php

namespace App\EndlessProxy\Channels;

use Illuminate\Broadcasting\Channel;

class CallCounterChannel extends Channel
{
	public function __construct()
	{
		parent::__construct(
			sha1(__CLASS__)
		);
	}
}