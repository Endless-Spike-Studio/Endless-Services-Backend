<?php

return [
	'storage' => [
		'base' => 'data/v2'
	],
	'gdcs' => [
		'data_per_page' => 10,
		'weekly_offset' => 100001,
		'custom_song_offset' => 10000000,
		'creator_points' => [
			'rated' => 1,
			'featured' => [
				'reward' => 2,
				'multiply_with_score' => false
			],
			'epic' => 4
		],
		'command' => [
			'start' => '!',
			'argument' => [
				'start' => ':',
				'delimiter' => '='
			]
		],
		'challenges' => [
			[
				'id' => 1,
				'name' => 'orb',
				'collect' => [
					'min' => 200,
					'max' => 2000
				],
				'names' => [],
				'reward' => [
					'every' => 100,
					'give' => 4
				]
			],
			[
				'id' => 2,
				'name' => 'coin',
				'collect' => [
					'min' => 2,
					'max' => 10
				],
				'names' => [],
				'reward' => [
					'every' => 2,
					'give' => 4
				]
			],
			[
				'id' => 3,
				'name' => 'star',
				'collect' => [
					'min' => 3,
					'max' => 10
				],
				'names' => [],
				'reward' => [
					'every' => 2,
					'give' => 4
				]
			]
		],
		'rewards' => [
			'small' => [
				'wait' => 3600,
				'orbs' => [
					'min' => 200,
					'max' => 400
				],
				'diamonds' => [
					'min' => 2,
					'max' => 10
				],
				'shards' => [
					'min' => 1,
					'max' => 6
				],
				'keys' => [
					'min' => 1,
					'max' => 6
				]
			],
			'big' => [
				'wait' => 14400,
				'orbs' => [
					'min' => 2000,
					'max' => 4000
				],
				'diamonds' => [
					'min' => 20,
					'max' => 100
				],
				'shards' => [
					'min' => 1,
					'max' => 6
				],
				'keys' => [
					'min' => 1,
					'max' => 6
				]
			]
		],
		'level_rating_suggestions' => [
			'stars' => [
				'enabled' => true,
				'assign_stars' => false,
				'overwrite' => true,
				'minimum_suggestion_count' => 3
			],
			'demon' => [
				'enabled' => true,
				'overwrite' => true,
				'minimum_suggestion_count' => 3
			],
			'suggest' => [
				'enabled' => true,
				'overwrite' => true,
				'minimum_suggestion_count' => 3
			]
		],
		'storages' => [
			'custom_songs' => [
				[
					'disk' => 'oss',
					'format' => 'gdcs/customSongs/{id}.mp3'
				]
			],
			'account_data' => [
				[
					'disk' => 'oss',
					'format' => 'gdcs/accounts/{id}.dat'
				]
			],
			'level_data' => [
				[
					'disk' => 'oss',
					'format' => 'gdcs/levels/{id}.dat'
				]
			]
		]
	],
	'proxy' => [
		'server' => 'http://clash:7890'
	],
	'gdproxy' => [
		'base' => 'https://www.boomlings.com/database',
		'storages' => [
			'custom_songs' => [
				[
					'disk' => 'oss',
					'format' => 'data/v2/gdproxy/customSongs/{id}.mp3'
				]
			]
		]
	],
	'ngproxy' => [
		'storages' => [
			'songs' => [
				[
					'disk' => 'oss',
					'format' => 'ngproxy/songs/{id}.mp3'
				]
			]
		]
	]
];