<?php

use Laravel\Octane\Contracts\OperationTerminated;
use Laravel\Octane\Events\RequestHandled;
use Laravel\Octane\Events\RequestReceived;
use Laravel\Octane\Events\RequestTerminated;
use Laravel\Octane\Events\TaskReceived;
use Laravel\Octane\Events\TaskTerminated;
use Laravel\Octane\Events\TickReceived;
use Laravel\Octane\Events\TickTerminated;
use Laravel\Octane\Events\WorkerErrorOccurred;
use Laravel\Octane\Events\WorkerStarting;
use Laravel\Octane\Events\WorkerStopping;
use Laravel\Octane\Listeners\CollectGarbage;
use Laravel\Octane\Listeners\EnsureUploadedFilesAreValid;
use Laravel\Octane\Listeners\EnsureUploadedFilesCanBeMoved;
use Laravel\Octane\Listeners\FlushTemporaryContainerInstances;
use Laravel\Octane\Listeners\FlushUploadedFiles;
use Laravel\Octane\Listeners\ReportException;
use Laravel\Octane\Listeners\StopWorkerIfNecessary;
use Laravel\Octane\Octane;

return [
    'server' => env('OCTANE_SERVER', 'roadrunner'),
    'https' => env('OCTANE_HTTPS', false),
    'listeners' => [
        WorkerStarting::class => [
            EnsureUploadedFilesAreValid::class,
            EnsureUploadedFilesCanBeMoved::class
        ],
        RequestReceived::class => [
            ...Octane::prepareApplicationForNextOperation(),
            ...Octane::prepareApplicationForNextRequest(),
        ],
        RequestHandled::class => [],
        RequestTerminated::class => [
            FlushUploadedFiles::class,
        ],
        TaskReceived::class => [
            ...Octane::prepareApplicationForNextOperation(),
        ],
        TaskTerminated::class => [],
        TickReceived::class => [
            ...Octane::prepareApplicationForNextOperation(),
        ],
        TickTerminated::class => [],
        OperationTerminated::class => [
            FlushTemporaryContainerInstances::class,
            CollectGarbage::class,
        ],
        WorkerErrorOccurred::class => [
            ReportException::class,
            StopWorkerIfNecessary::class,
        ],
        WorkerStopping::class => [],
    ],
    'warm' => [
        ...Octane::defaultServicesToWarm(),
    ],
    'flush' => [],
    'cache' => [
        'rows' => 1000,
        'bytes' => 10000,
    ],
    'tables' => [
        'example:1000' => [
            'name' => 'string:1000',
            'votes' => 'int',
        ],
    ],
    'watch' => [
        'app',
        'bootstrap',
        'config',
        'database',
        'public/**/*.php',
        'resources/**/*.php',
        'routes',
        'composer.lock',
        '.env',
    ],
    'garbage' => 50,
    'max_execution_time' => 30,
];
