<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\Game\Algorithm\Salts;
use App\Enums\GDCS\Game\Objects\LevelPackObject;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\LevelPackFetchRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\LevelPack;
use App\Services\Game\AlgorithmService;
use App\Services\Game\BaseGameService;
use App\Services\Game\ObjectService;

class LevelPackController extends Controller
{
    use GameLog;

    public function index(LevelPackFetchRequest $request): string
    {
        $data = $request->validated();
        $hashes = [];

        $query = LevelPack::query();
        $count = $query->count();

        $this->logGame(__('gdcn.game.action.level_pack_index_success'));
        return implode('#', [
            $query->forPage(++$data['page'], BaseGameService::$perPage)
                ->get()
                ->map(function (LevelPack $pack) use (&$hashes) {
                    $hashes[] = implode(null, [
                        substr($pack->id, 0, 1),
                        substr($pack->id, -1, 1),
                        $pack->stars,
                        $pack->coins,
                    ]);

                    return ObjectService::merge([
                        LevelPackObject::ID => $pack->id,
                        LevelPackObject::NAME => $pack->name,
                        LevelPackObject::LEVELS => $pack->levels,
                        LevelPackObject::STARS => $pack->stars,
                        LevelPackObject::COINS => $pack->coins,
                        LevelPackObject::DIFFICULTY => $pack->difficulty->value,
                        LevelPackObject::TEXT_COLOR => $pack->text_color,
                        LevelPackObject::BAR_COLOR => $pack->bar_color,
                    ], ':');
                })->join('|'),
            AlgorithmService::genPage($data['page'], $count),
            sha1(implode(null, $hashes) . Salts::LEVEL->value),
        ]);
    }
}
