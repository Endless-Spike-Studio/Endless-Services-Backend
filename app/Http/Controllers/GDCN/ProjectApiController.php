<?php

namespace App\Http\Controllers\GDCN;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCN\ProjectUpdateRequest;

class ProjectApiController extends Controller
{
    public function update(ProjectUpdateRequest $request): void
    {
        $request->validated();

        $commands = [
            'cd /app',
            'git pull',
            'composer install --no-dev',
            'php artisan optimize:clear',
            'php artisan migrate',
            'pnpm install',
            'pnpm run build',
            'php artisan static:upload',
            'php artisan optimize',
            'php artisan octane:reload'
        ];

        foreach ($commands as $command) {
            exec($command);
        }
    }
}
