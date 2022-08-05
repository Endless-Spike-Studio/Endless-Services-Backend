<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],
        'oss' => [
            'driver' => 'oss',
            'access_key_id' => env('OSS_ACCESS_KEY_ID'),
            'access_key_secret' => env('OSS_ACCESS_KEY_SECRET'),
            'bucket' => env('OSS_BUCKET'),
            'endpoint' => env('OSS_ENDPOINT'),
            'internal' => env('OSS_INTERNAL'),
            'domain' => env('OSS_DOMAIN'),
            'use_ssl' => env('OSS_SSL', true),
            'reverse_proxy' => env('OSS_REVERSE_PROXY', false),
        ],
    ],
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
