<?php

namespace App\Http\Controllers\GDCN;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCN\ProjectUpdateRequest;
use Illuminate\Support\Facades\Log;

class ProjectApiController extends Controller
{
    public function update(ProjectUpdateRequest $request): void
    {
        $request->validated();
        exec('php /app/vendor/bin/envoy run deploy', $output);

        Log::channel('daily')
            ->notice('执行 Github 更新钩子', [
                'output' => $output
            ]);
    }
}
