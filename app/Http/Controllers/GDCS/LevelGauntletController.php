<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\Game\Algorithm\Salts;
use App\Enums\GDCS\Game\Objects\LevelGauntletObject;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\LevelGauntletFetchRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\LevelGauntlet;
use App\Services\Game\ObjectService;

class LevelGauntletController extends Controller
{
    use GameLog;

    public function index(LevelGauntletFetchRequest $request): string
    {
        $request->validated();

        $query = LevelGauntlet::query();
        $hashes = [];

        $this->logGame(__('gdcn.game.action.level_gauntlet_index_success'));
        return implode('#', [
            $query->get()
                ->map(function (LevelGauntlet $gauntlet) use (&$hashes) {
                    $levels = implode(null, [
                        $gauntlet->level1_id,
                        $gauntlet->level2_id,
                        $gauntlet->level3_id,
                        $gauntlet->level4_id,
                        $gauntlet->level5_id,
                    ]);

                    $hashes[] = implode(null, [
                        $gauntlet->id,
                        $levels,
                    ]);

                    return ObjectService::merge([
                        LevelGauntletObject::ID => $gauntlet->id,
                        LevelGauntletObject::LEVELS => $levels,
                    ], ':');
                })->join('|'),
            sha1(implode(null, $hashes) . Salts::LEVEL->value),
        ]);
    }
}
