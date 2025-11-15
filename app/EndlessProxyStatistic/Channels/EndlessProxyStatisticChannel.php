<?php

namespace App\EndlessProxyStatistic\Channels;

use Illuminate\Broadcasting\Channel;

class EndlessProxyStatisticChannel extends Channel
{
	public function __construct()
	{
		parent::__construct('endless_proxy.statistic');
	}
}