<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\LeaderboardFetchRequest;
use App\Models\GDCS\UserScore;
use GDCN\GDObject\GDObject;

class LeaderboardController extends Controller
{
    public function fetchAll(LeaderboardFetchRequest $request): int|string
    {
        $data = $request->validated();

        $query = UserScore::query();
        switch ($data['type']) {
            case 'top':
                $query->orderByDesc('stars');
                break;
            case 'friends':
                if (!$request->auth()) {
                    return Response::LEADERBOARD_FETCH_FAILED_ACCOUNT_VALIDATE_FAILED->value;
                }

                $query->whereIn('user_id', $request->account->friend_user_ids_with_self);
                break;
            case 'creators':
                $query->orderByDesc('creator_points');
                break;
            case 'relative':
                $currentUserStars = $request->user->score->stars;
                $top = UserScore::query()
                        ->where('stars', '<=', $currentUserStars)
                        ->count() - 1;

                $count = floor($data['count'] / 2);
                $query->where('stars', '<=', $currentUserStars)
                    ->union(
                        UserScore::query()
                            ->where('stars', '>=', $currentUserStars)
                            ->take($count)
                            ->toBase()
                    )->take($count)
                    ->orderBy('stars');
                break;
            default:
                return Response::LEADERBOARD_FETCH_FAILED_TYPE_NOT_FOUND->value;
        }

        $top ??= 0;
        return $query->take($data['count'])
            ->get()
            ->unique('user_id')
            ->map(function (UserScore $score) use (&$top) {
                return GDObject::merge([
                    1 => $score->user->name,
                    2 => $score->user->id,
                    3 => $score->stars,
                    4 => $score->demons,
                    6 => ++$top,
                    8 => $score->creator_points,
                    9 => $score->icon,
                    10 => $score->color1,
                    11 => $score->color2,
                    13 => $score->coins,
                    14 => $score->icon_type,
                    15 => $score->special,
                    16 => $score->user->uuid,
                    17 => $score->user_coins,
                    46 => $score->demons
                ], ':');
            })->join('|');
    }
}
