<?php

return [
    'storages' => [
        'songs' => [
            [
                'disk' => 'oss',
                'format' => 'storages/songs/{id}.mp3'
            ]
        ],
        'game' => [
            'accounts' => [
                [
                    'disk' => 'oss',
                    'format' => 'storages/game/accounts/{id}.dat'
                ]
            ],
            'levels' => [
                [
                    'disk' => 'oss',
                    'format' => 'storages/game/levels/{id}.dat'
                ]
            ]
        ]
    ]
];
