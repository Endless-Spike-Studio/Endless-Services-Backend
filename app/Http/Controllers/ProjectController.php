<?php

namespace App\Http\Controllers;

class ProjectController extends Controller
{
    public function update(): void
    {
        $this->dispatchSync(function () {
            return exec('/app/vendor/bin/envoy run deploy-backend');
        });
    }
}
