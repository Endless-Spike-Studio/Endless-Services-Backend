<?php

return [
    'perPage' => 10,
    'custom_song_offset' => 10000000,
    'command' => [
        'start' => '!',
    ],
    'suggestion' => [
        'minimum_count' => [
            'rate' => 10,
            'demon' => 10,
            'mod' => 3,
        ],
    ],
    'reward' => [
        'small' => [
            'wait' => 3600,
            'orbs' => [200, 400],
            'diamonds' => [2, 10],
            'shards' => [1, 6],
            'keys' => [1, 6],
        ],
        'big' => [
            'wait' => 14400,
            'orbs' => [2000, 4000],
            'diamonds' => [20, 100],
            'shards' => [1, 6],
            'keys' => [1, 6],
        ],
    ],
    'challenge' => [
        'orbs' => [
            'collect' => [200, 2000],
            'names' => ['orbs! orbs! orbs!'],
            'reward' => [100, 4],
        ],
        'coins' => [
            'collect' => [2, 10],
            'names' => ['coins! coins! coins!'],
            'reward' => [2, 4],
        ],
        'stars' => [
            'collect' => [3, 10],
            'names' => ['stars! stars! stars!'],
            'reward' => [2, 4],
        ],
    ],
    'storages' => [
        'saveData' => [
            [
                'disk' => 'oss',
                'format' => 'gdcs/saveData/@.dat',
            ],
        ],
        'levelData' => [
            [
                'disk' => 'oss',
                'format' => 'gdcs/levelData/@.dat',
            ],
        ],
        'customSongData' => [
            [
                'disk' => 'oss',
                'format' => 'gdcs/customSongData/@.mp3',
            ],
        ],
    ],
    'max_temp_upload_access_count' => 2,
];
