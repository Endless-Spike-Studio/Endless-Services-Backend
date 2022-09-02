<?php

return [
    'configs' => [
        'default' => [
            'entrypoints' => [
                'ssr' => 'resources/scripts/ssr.ts',
                'paths' => [
                    'resources/scss/main.scss',
                    'resources/scripts/main.ts',
                    'resources/js/app.js',
                ],
                'ignore' => '/\\.(d\\.ts|json)$/',
            ],
            'dev_server' => [
                'enabled' => true,
                'url' => env('DEV_SERVER_URL', 'http://localhost:3000'),
                'ping_before_using_manifest' => true,
                'ping_url' => null,
                'ping_timeout' => 1,
                'key' => env('DEV_SERVER_KEY'),
                'cert' => env('DEV_SERVER_CERT'),
            ],
            'build_path' => 'https://cdn.geometrydashchinese.com/static/website'
        ],
    ],
    'aliases' => [
        '@' => 'resources',
    ],
    'commands' => [
        'artisan' => [
            'vite:tsconfig',
        ],
        'shell' => [],
    ],
    'testing' => [
        'use_manifest' => false,
    ],
    'env_prefixes' => ['VITE_', 'MIX_', 'SCRIPT_'],
    'interfaces' => [
        'heartbeat_checker' => Innocenzi\Vite\HeartbeatCheckers\HttpHeartbeatChecker::class,
        'tag_generator' => Innocenzi\Vite\TagGenerators\CallbackTagGenerator::class,
        'entrypoints_finder' => Innocenzi\Vite\EntrypointsFinder\DefaultEntrypointsFinder::class,
    ],
    'default' => env('VITE_DEFAULT_CONFIG', 'default'),
];
