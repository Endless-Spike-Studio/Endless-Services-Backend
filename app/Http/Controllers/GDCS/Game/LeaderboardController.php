<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\Objects\LeaderboardObject;
use App\Enums\GDCS\Game\Objects\UserObject;
use App\Enums\GDCS\Game\Parameters\LeaderboardFetchType;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\LeaderboardFetchRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\AccountFriend;
use App\Models\GDCS\UserScore;
use App\Services\Game\ObjectService;

class LeaderboardController extends Controller
{
    use GameLog;

    /**
     * @throws GeometryDashChineseServerException
     */
    public function index(LeaderboardFetchRequest $request): int|string
    {
        $data = $request->validated();

        $top = 0;
        $query = UserScore::query();

        switch ($data['type']) {
            case LeaderboardFetchType::TOP:
                $query->orderByDesc('stars');
                $query->take($data['count']);
                $result = $query->get();
                break;
            case LeaderboardFetchType::FRIENDS:
                if (empty($request->account)) {
                    throw new GeometryDashChineseServerException(__('gdcn.game.error.leaderboard_fetch_failed_need_login'), gameResponse: Response::GAME_LEADERBOARD_FETCH_FAILED_NEED_LOGIN->value);
                }

                $query->whereIn(
                    'user_id',
                    $request->account->friends()
                        ->with('account.user', 'friend_account.user')
                        ->get()
                        ->map(function (AccountFriend $friend) use ($request) {
                            return ($friend->account->is($request->account) ? $friend->friend_account : $friend->account)->user->id;
                        })->push($request->user->id)
                );

                $query->take($data['count']);
                $result = $query->get();
                break;
            case LeaderboardFetchType::CREATORS:
                $query->orderByDesc('creator_points');
                $query->take($data['count']);
                $result = $query->get();
                break;
            case LeaderboardFetchType::RELATIVE:
                $top = UserScore::query()
                        ->where('stars', '>=', $request->user->score->stars)
                        ->count() - 1;

                $result = collect([$request->user])
                    ->prepend(
                        UserScore::query()
                            ->where('stars', '<=', $request->user->score->stars)
                            ->with('user')
                            ->take($data['count'] / 2)
                            ->get()
                            ->pluck('user')
                    )->push(
                        UserScore::query()
                            ->where('stars', '>=', $request->user->score->stars)
                            ->with('user')
                            ->take($data['count'] / 2)
                            ->get()
                            ->pluck('user')
                    )->unique('user_id');
                break;
            default:
                throw new GeometryDashChineseServerException(__('gdcn.game.error.leaderboard_fetch_failed_invalid_type'), gameResponse: Response::GAME_LEADERBOARD_FETCH_FAILED_INVALID_TYPE->value);
        }

        $this->logGame(__('gdcn.game.action.leaderboard_fetch_success'));
        return $result->map(function (UserScore $score) use (&$top) {
            return ObjectService::merge([
                LeaderboardObject::USER_NAME => $score->user->name,
                LeaderboardObject::USER_ID => $score->user->id,
                UserObject::STARS => $score->stars,
                UserObject::DEMONS => $score->demons,
                LeaderboardObject::RANKING => ++$top,
                UserObject::CREATOR_POINTS => $score->creator_points,
                LeaderboardObject::USER_ICON_ID => $score->icon,
                LeaderboardObject::USER_COLOR_ID => $score->color1,
                LeaderboardObject::USER_SECOND_COLOR_ID => $score->color2,
                UserObject::COINS => $score->coins,
                LeaderboardObject::USER_ICON_TYPE => $score->icon_type,
                LeaderboardObject::USER_SPECIAL => $score->special,
                LeaderboardObject::USER_UUID => $score->user->uuid,
                UserObject::USER_COINS => $score->user_coins,
                UserObject::DIAMONDS => $score->diamonds,
            ], ':');
        })->join('|');
    }
}
