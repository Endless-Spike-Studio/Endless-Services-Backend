<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\LevelGauntletFetchRequest;
use App\Models\GDCS\LevelGauntlet;
use GeometryDashChinese\enums\Salts;
use GeometryDashChinese\GeometryDashObject;

class LevelGauntletController extends Controller
{
    public function fetchAll(LevelGauntletFetchRequest $request): string
    {
        $request->validated();
        $hash = null;

        return implode('#', [
            LevelGauntlet::query()
                ->get()
                ->map(function (LevelGauntlet $gauntlet) use (&$hash) {
                    $levels = implode(null, [
                        $gauntlet->level1_id,
                        $gauntlet->level2_id,
                        $gauntlet->level3_id,
                        $gauntlet->level4_id,
                        $gauntlet->level5_id,
                    ]);

                    $hash .= implode(null, [
                        $gauntlet->id,
                        $levels,
                    ]);

                    return GeometryDashObject::merge([
                        1 => $gauntlet->id,
                        3 => $levels,
                    ], ':');
                })->join('|'),
            sha1($hash . Salts::LEVEL->value),
        ]);
    }
}
