<?php

return [
    'ssr' => [
        'enabled' => false,
        'url' => 'http://127.0.0.1:13714/render',
    ],
    'testing' => [
        'ensure_pages_exist' => true,
        'page_paths' => [
            resource_path('views/pages'),
        ],
        'page_extensions' => [
            'js',
            'jsx',
            'svelte',
            'ts',
            'tsx',
            'vue',
        ],
    ],
];
