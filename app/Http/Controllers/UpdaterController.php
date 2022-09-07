<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateProjectJob;

class UpdaterController extends Controller
{
    public function run(string $secret)
    {
        if (config('gdcn.updater.secret') !== $secret) {
            abort(403);
        }

        UpdateProjectJob::dispatchAfterResponse();
    }
}
