<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\UserScoreUpdateRequest;
use App\Http\Traits\GameLog;

class UserScoreController extends Controller
{
    use GameLog;

    public function update(UserScoreUpdateRequest $request): int
    {
        $data = $request->validated();

        $request->user->score->update([
            'stars' => $data['stars'],
            'demons' => $data['demons'],
            'diamonds' => $data['diamonds'],
            'icon' => $data['icon'],
            'color1' => $data['color1'],
            'color2' => $data['color2'],
            'icon_type' => $data['iconType'],
            'coins' => $data['coins'],
            'user_coins' => $data['userCoins'],
            'special' => $data['special'],
            'acc_icon' => $data['accIcon'],
            'acc_ship' => $data['accShip'],
            'acc_ball' => $data['accBall'],
            'acc_bird' => $data['accBird'],
            'acc_dart' => $data['accDart'],
            'acc_robot' => $data['accRobot'],
            'acc_glow' => $data['accGlow'],
            'acc_spider' => $data['accSpider'],
            'acc_explosion' => $data['accExplosion']
        ]);

        $this->logGame(__('gdcn.game.action.user_score_update_success'));
        return $request->user->id;
    }
}
