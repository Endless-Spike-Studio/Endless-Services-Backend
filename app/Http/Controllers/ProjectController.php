<?php

namespace App\Http\Controllers;

class ProjectController extends Controller
{
    public function update(): void
    {
        dispatch(function () {
            shell_exec('/app/vendor/bin/envoy run deploy-backend');
        })->afterResponse();
    }
}
