<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateProjectJob;

class ProjectController extends Controller
{
    public function update(string $secret)
    {
        if (config('gdcn.updater.secret') !== $secret) {
            abort(403);
        }

        UpdateProjectJob::dispatchSync();
    }
}
