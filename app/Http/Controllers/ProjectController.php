<?php

namespace App\Http\Controllers;

class ProjectController extends Controller
{
    public static function update()
    {
        exec('/app/vendor/bin/envoy run deploy-backend');
    }
}
