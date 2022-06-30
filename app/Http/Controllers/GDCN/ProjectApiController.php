<?php

namespace App\Http\Controllers\GDCN;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCN\ProjectUpdateRequest;

class ProjectApiController extends Controller
{
    public function update(ProjectUpdateRequest $request): void
    {
        $request->validated();
        exec('php /app/vendor/bin/envoy run update');
    }
}
