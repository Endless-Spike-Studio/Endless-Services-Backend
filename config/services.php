<?php

use App\GeometryDash\Enums\GeometryDashQuestCollectTypes;

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
			],
			'quests' => [
				'count' => env('ENDLESS_SERVER_QUESTS_COUNT', 3),
				'generator' => [
					GeometryDashQuestCollectTypes::ORB->value => [
						'collect' => [
							'min' => env('ENDLESS_SERVER_QUESTS_GENERATOR_ORB_COLLECT_MIN', 200),
							'max' => env('ENDLESS_SERVER_QUESTS_GENERATOR_ORB_COLLECT_MAX', 2000)
						],
						'names' => explode(
							',',
							env('ENDLESS_SERVER_QUESTS_GENERATOR_ORB_NAMES', '')
						),
						'reward' => [
							'every' => env('ENDLESS_SERVER_QUESTS_GENERATOR_ORB_REWARD_EVERY', 100),
							'give' => env('ENDLESS_SERVER_QUESTS_GENERATOR_ORB_REWARD_GIVE', 4)
						]
					],
					GeometryDashQuestCollectTypes::COIN->value => [
						'collect' => [
							'min' => env('ENDLESS_SERVER_QUESTS_GENERATOR_COIN_COLLECT_MIN', 2),
							'max' => env('ENDLESS_SERVER_QUESTS_GENERATOR_COIN_COLLECT_MAX', 10)
						],
						'names' => explode(
							',',
							env('ENDLESS_SERVER_QUESTS_GENERATOR_COIN_NAMES', '')
						),
						'reward' => [
							'every' => env('ENDLESS_SERVER_QUESTS_GENERATOR_COIN_REWARD_EVERY', 2),
							'give' => env('ENDLESS_SERVER_QUESTS_GENERATOR_COIN_REWARD_GIVE', 4)
						]
					],
					GeometryDashQuestCollectTypes::STAR->value => [
						'collect' => [
							'min' => env('ENDLESS_SERVER_QUESTS_GENERATOR_STAR_COLLECT_MIN', 3),
							'max' => env('ENDLESS_SERVER_QUESTS_GENERATOR_STAR_COLLECT_MAX', 10)
						],
						'names' => explode(
							',',
							env('ENDLESS_SERVER_QUESTS_GENERATOR_STAR_NAMES', '')
						),
						'reward' => [
							'every' => env('ENDLESS_SERVER_QUESTS_GENERATOR_STAR_REWARD_EVERY', 2),
							'give' => env('ENDLESS_SERVER_QUESTS_GENERATOR_STAR_REWARD_GIVE', 4)
						]
					]
				]
			],
			'level_data' => [
				'storage' => [
					'disk' => env('ENDLESS_SERVER_LEVEL_DATA_STORAGE_DISK', 'oss'),
					'format' => env('ENDLESS_SERVER_LEVEL_DATA_STORAGE_FORMAT', 'endless_server/level_data/{id}.dat')
				]
			],
			'level_rating_suggest' => [
				'enabled' => env('ENDLESS_SERVER_LEVEL_RATING_SUGGEST_ENABLED', true),
				'min_votes' => env('ENDLESS_SERVER_LEVEL_RATING_SUGGEST_MIN_VOTES', 10),
				'overrideable' => env('ENDLESS_SERVER_LEVEL_RATING_SUGGEST_OVERRIDABLE', true)
			],
			'demon_rating_suggest' => [
				'enabled' => env('ENDLESS_SERVER_DEMON_RATING_SUGGEST_ENABLED', true),
				'min_votes' => env('ENDLESS_SERVER_DEMON_RATING_SUGGEST_MIN_VOTES', 10),
				'overrideable' => env('ENDLESS_SERVER_DEMON_RATING_SUGGEST_OVERRIDABLE', true)
			],
			'mod_level_rating_suggest' => [
				'enabled' => env('ENDLESS_SERVER_MOD_LEVEL_RATING_SUGGEST_ENABLED', true),
				'min_votes' => env('ENDLESS_SERVER_MOD_LEVEL_RATING_SUGGEST_MIN_VOTES', 3),
				'overrideable' => env('ENDLESS_SERVER_MOD_LEVEL_RATING_SUGGEST_OVERRIDABLE', true)
			],
			'mod_demon_rating_suggest' => [
				'enabled' => env('ENDLESS_SERVER_MOD_DEMON_RATING_SUGGEST_ENABLED', true),
				'min_votes' => env('ENDLESS_SERVER_MOD_DEMON_RATING_SUGGEST_MIN_VOTES', 3),
				'overrideable' => env('ENDLESS_SERVER_MOD_DEMON_RATING_SUGGEST_OVERRIDABLE', true)
			]
		]
	]
];