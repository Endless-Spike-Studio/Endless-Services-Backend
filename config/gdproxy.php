<?php

return [
    'base_url' => 'http://www.boomlings.com/database',
    'custom_song_offset' => 10000000,
    'storages' => [
        'customSongData' => [
            [
                'disk' => 'oss',
                'format' => 'gdproxy/customSongData/@.mp3',
            ]
        ]
    ]
];
