<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class ProjectController extends Controller
{
    public function update(): void
    {
        Artisan::queue('project:update');
    }
}
