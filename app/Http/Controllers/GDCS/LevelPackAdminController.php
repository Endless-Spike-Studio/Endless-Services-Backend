<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelPack;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LevelPackAdminController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('GDCS/Admin/LevelPack/List', [
            'packs' => LevelPack::all(['id', 'name', 'levels', 'stars', 'coins', 'difficulty', 'text_color', 'bar_color', 'created_at'])
                ->map(function (LevelPack $pack) {
                    $levels = Level::findMany(explode(',', $pack->levels), ['id', 'name']);

                    $pack->setAttribute('levels_info', $levels);
                    $pack->setAttribute('difficulty_name', $pack->difficulty->name);
                    return $pack;
                })
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(LevelPack $pack)
    {
        //
    }

    public function edit(LevelPack $pack)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(LevelPack $pack)
    {
        //
    }
}
