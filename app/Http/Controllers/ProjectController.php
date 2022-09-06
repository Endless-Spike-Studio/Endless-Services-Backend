<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class ProjectController extends Controller
{
    public function update(): int
    {
        return Artisan::call('project:update');
    }
}
