<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\LevelPackFetchRequest;
use App\Models\GDCS\LevelPack;
use GeometryDashChinese\enums\Salts;
use GeometryDashChinese\GeometryDashAlgorithm;
use GeometryDashChinese\GeometryDashObject;

class LevelPackController extends Controller
{
    public function fetchAll(LevelPackFetchRequest $request): string
    {
        $data = $request->validated();
        $perPage = config('gdcs.perPage', 10);
        $hash = null;

        return implode('#', [
            LevelPack::query()
                ->forPage(++$data['page'], $perPage)
                ->get()
                ->map(function (LevelPack $pack) use (&$hash) {
                    $hash .= implode(null, [
                        substr($pack->id, 0, 1),
                        substr($pack->id, -1, 1),
                        $pack->stars,
                        $pack->coins,
                    ]);

                    return GeometryDashObject::merge([
                        1 => $pack->id,
                        2 => $pack->name,
                        3 => $pack->levels,
                        4 => $pack->stars,
                        5 => $pack->coins,
                        6 => $pack->difficulty->value,
                        7 => $pack->text_color,
                        8 => $pack->bar_color,
                    ], ':');
                })->join('|'),
            GeometryDashAlgorithm::genPage($data['page'], LevelPack::query()
                ->count(), $perPage),
            sha1($hash . Salts::LEVEL->value),
        ]);
    }
}
