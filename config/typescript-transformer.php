<?php

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Hybridly\Support\TypeScriptTransformer\DataResourceTypeScriptCollector;
use Spatie\LaravelData\Support\TypeScriptTransformer\DataTypeScriptCollector;
use Spatie\LaravelData\Support\TypeScriptTransformer\DataTypeScriptTransformer;
use Spatie\LaravelTypeScriptTransformer\Transformers\SpatieStateTransformer;
use Spatie\TypeScriptTransformer\Collectors\DefaultCollector;
use Spatie\TypeScriptTransformer\Transformers\DtoTransformer;
use Spatie\TypeScriptTransformer\Transformers\EnumTransformer;
use Spatie\TypeScriptTransformer\Transformers\SpatieEnumTransformer;
use Spatie\TypeScriptTransformer\Writers\TypeDefinitionWriter;

return [
    'auto_discover_types' => [
        app_path()
    ],
    'collectors' => [
        DefaultCollector::class,
        DataResourceTypeScriptCollector::class,
        DataTypeScriptCollector::class
    ],
    'transformers' => [
        SpatieStateTransformer::class,
        SpatieEnumTransformer::class,
        DtoTransformer::class,
        DataTypeScriptTransformer::class,
        EnumTransformer::class
    ],
    'default_type_replacements' => [
        DateTime::class => 'string',
        DateTimeImmutable::class => 'string',
        CarbonImmutable::class => 'string',
        Carbon::class => 'string'
    ],
    'output_file' => resource_path('types/generated.d.ts'),
    'writer' => TypeDefinitionWriter::class,
    'formatter' => null,
    'transform_to_native_enums' => false
];
