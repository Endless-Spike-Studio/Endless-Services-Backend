<?php

return [
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
    'game' => [
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
        ]
    ],
];
