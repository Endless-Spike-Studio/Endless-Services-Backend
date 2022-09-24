<?php

return [
    'updater' => [
        'secret' => env('UPDATER_SECRET')
    ],
    'storages' => [
        'songs' => [
            [
                'disk' => 'oss',
                'format' => 'storages/songs/{id}.mp3',
            ],
        ],
        'game' => [
            'accounts' => [
                [
                    'disk' => 'oss',
                    'format' => 'storages/game/accounts/{id}.dat',
                ],
            ],
            'levels' => [
                [
                    'disk' => 'oss',
                    'format' => 'storages/game/levels/{id}.dat',
                ],
            ],
        ],
    ],
    'proxy' => [
        'url' => env('CLASH_URL'),
        'api' => [
            'url' => env('CLASH_API'),
            'secret' => env('CLASH_API_SECRET')
        ],
        'base' => 'http://www.boomlings.com/database'
    ],
    'game' => [
        'per_page' => 10,
        'custom_song_offset' => 10000000,
        'creator_points' => [
            'rated' => 1,
            'featured' => [
                'reward' => 2,
                'multiply_with_score' => false,
            ],
            'epic' => 4,
        ],
        'command' => [
            'start' => '!',
            'argument' => [
                'start' => ':',
                'delimiter' => '='
            ],
            'boolean_mapper' => [
                'false' => false,
                'true' => true,
                'no' => false,
                'yes' => true,
                'off' => false,
                'on' => true,
                '0' => false,
                '1' => true,
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
                ],
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
            ],
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
                ],
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
                ],
            ],
        ],
        'level_rating_suggestions' => [
            'stars' => [
                'enabled' => true,
                'assign_stars' => false,
                'overwrite_able' => true,
                'minimum_count' => 3
            ],
            'demon' => [
                'enabled' => true,
                'overwrite_able' => true,
                'minimum_count' => 3
            ],
            'suggest' => [
                'enabled' => true,
                'overwrite_able' => true,
                'minimum_count' => 3
            ]
        ]
    ],
];
