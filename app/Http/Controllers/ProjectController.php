<?php

namespace App\Http\Controllers;

class ProjectController extends Controller
{
    public static function update()
    {
        exec('/vendor/bin/envoy run deploy-backend');
    }
}
