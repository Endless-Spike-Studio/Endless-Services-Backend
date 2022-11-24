<?php

use Hybridly\Hybridly;

return [
    'root_view' => Hybridly::DEFAULT_ROOT_VIEW,
    'router' => [
        'allowed_vendors' => [],
        'exclude' => []
    ],
    'force_case' => [
        'input' => null,
        'output' => null
    ],
    'i18n' => [
        'lang_path' => base_path('lang'),
        'locales_path' => resource_path('i18n/locales'),
        'file_name_template' => '{locale}.json',
        'file_name' => 'locales.json'
    ],
    'testing' => [
        'ensure_pages_exist' => true,
        'view_finder' => null,
        'page_paths' => [
            resource_path('views/pages')
        ],
        'page_extensions' => [
            'js',
            'jsx',
            'ts',
            'tsx',
            'vue'
        ]
    ]
];
