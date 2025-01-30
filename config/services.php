<?php

return [
	'endless' => [
		'proxy' => [
			'server' => env('ENDLESS_PROXY_SERVER'),
			'network_log_enabled' => env('ENDLESS_PROXY_NETWORK_LOG_ENABLED', true),
			'retry' => [
				'times' => env('ENDLESS_PROXY_RETRY_TIMES', 10),
				'delay' => env('ENDLESS_PROXY_RETRY_DELAY', 0)
			],
			'geometry_dash' => [
				'base' => env('ENDLESS_PROXY_GEOMETRY_DASH_BASE', 'https://www.boomlings.com/database'),
				'custom_contents' => [
					'storage' => [
						'disk' => env('ENDLESS_PROXY_GEOMETRY_DASH_CUSTOM_CONTENTS_STORAGE_DISK', 'oss'),
						'format' => env('ENDLESS_PROXY_GEOMETRY_DASH_CUSTOM_CONTENTS_STORAGE_FORMAT', 'endless_proxy/geometry_dash/custom_contents/{path}')
					]
				]
			],
			'newgrounds' => [
				'audios' => [
					'storage' => [
						'disk' => env('ENDLESS_PROXY_NEWGROUNDS_AUDIOS_STORAGE_DISK', 'oss'),
						'format' => env('ENDLESS_PROXY_NEWGROUNDS_AUDIOS_STORAGE_FORMAT', 'endless_proxy/newgrounds/audios/{id}.mp3')
					]
				]
			]
		],
		'server' => [
			'per_page' => env('ENDLESS_SERVER_PER_PAGE', 10),
			'account_data' => [
				'storage' => [
					'disk' => env('ENDLESS_SERVER_ACCOUNT_DATA_STORAGE_DISK', 'oss'),
					'format' => env('ENDLESS_SERVER_ACCOUNT_DATA_STORAGE_FORMAT', 'endless_server/account_data/{id}.dat')
				]
			],
			'rewards' => [
				'small' => [
					'wait' => env('ENDLESS_SERVER_REWARDS_SMALL_WAIT', 3600),
					'orbs' => [
						'min' => env('ENDLESS_SERVER_REWARDS_SMALL_ORBS_MIN', 200),
						'max' => env('ENDLESS_SERVER_REWARDS_SMALL_ORBS_MAX', 400)
					],
					'diamonds' => [
						'min' => env('ENDLESS_SERVER_REWARDS_SMALL_DIAMONDS_MIN', 2),
						'max' => env('ENDLESS_SERVER_REWARDS_SMALL_DIAMONDS_MAX', 10)
					],
					'shards' => [
						'min' => env('ENDLESS_SERVER_REWARDS_SMALL_SHARDS_MIN', 1),
						'max' => env('ENDLESS_SERVER_REWARDS_SMALL_SHARDS_MAX', 6)
					],
					'keys' => [
						'min' => env('ENDLESS_SERVER_REWARDS_BIG_KEYS_MIN', 1),
						'max' => env('ENDLESS_SERVER_REWARDS_BIG_KEYS_MAX', 6)
					]
				],
				'big' => [
					'wait' => env('ENDLESS_SERVER_REWARDS_BIG_WAIT', 14400),
					'orbs' => [
						'min' => env('ENDLESS_SERVER_REWARDS_BIG_ORBS_MIN', 2000),
						'max' => env('ENDLESS_SERVER_REWARDS_BIG_ORBS_MAX', 4000)
					],
					'diamonds' => [
						'min' => env('ENDLESS_SERVER_REWARDS_BIG_DIAMONDS_MIN', 20),
						'max' => env('ENDLESS_SERVER_REWARDS_BIG_DIAMONDS_MAX', 100)
					],
					'shards' => [
						'min' => env('ENDLESS_SERVER_REWARDS_BIG_SHARDS_MIN', 1),
						'max' => env('ENDLESS_SERVER_REWARDS_BIG_SHARDS_MAX', 6)
					],
					'keys' => [
						'min' => env('ENDLESS_SERVER_REWARDS_BIG_KEYS_MIN', 1),
						'max' => env('ENDLESS_SERVER_REWARDS_BIG_KEYS_MAX', 6)
					]
				]
			]
		]
	]
];