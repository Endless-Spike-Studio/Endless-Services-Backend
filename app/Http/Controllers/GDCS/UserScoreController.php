<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\UserScoreUpdateRequest;

class UserScoreController extends Controller
{
    public function update(UserScoreUpdateRequest $request): int
    {
        $data = $request->validated();
        $user = $request->user;

        $score = $user->score;
        $score->stars = $data['stars'];
        $score->demons = $data['demons'];
        $score->diamonds = $data['diamonds'];
        $score->icon = $data['icon'];
        $score->color1 = $data['color1'];
        $score->color2 = $data['color2'];
        $score->icon_type = $data['iconType'];
        $score->coins = $data['coins'];
        $score->user_coins = $data['userCoins'];
        $score->special = $data['special'];
        $score->acc_icon = $data['accIcon'];
        $score->acc_ship = $data['accShip'];
        $score->acc_ball = $data['accBall'];
        $score->acc_bird = $data['accBird'];
        $score->acc_dart = $data['accDart'];
        $score->acc_robot = $data['accRobot'];
        $score->acc_glow = $data['accGlow'];
        $score->acc_spider = $data['accSpider'];
        $score->acc_explosion = $data['accExplosion'];
        $score->save();

        return $user->id;
    }
}
