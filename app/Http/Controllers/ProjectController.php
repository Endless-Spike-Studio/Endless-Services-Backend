<?php

namespace App\Http\Controllers;

class ProjectController extends Controller
{
    public function update(): void
    {
        shell_exec('/app/vendor/bin/envoy run deploy-backend');
    }
}
