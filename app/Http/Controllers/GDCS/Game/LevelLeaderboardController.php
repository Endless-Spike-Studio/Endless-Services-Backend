<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\Objects\LeaderboardObject;
use App\Enums\GDCS\Game\Parameters\LevelLeaderboardFetchType;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\LevelLeaderboardUploadAndFetchRequest;
use App\Models\GDCS\AccountFriend;
use App\Models\GDCS\LevelScore;
use App\Services\Game\ObjectService;

class LevelLeaderboardController extends Controller
{
    /**
     * @throws GeometryDashChineseServerException
     */
    public function fetch(LevelLeaderboardUploadAndFetchRequest $request): int|string
    {
        $data = $request->validated();
        $this->update($request);

        $top = 0;
        $query = LevelScore::query();
        $query->where('level_id', $data['levelID']);

        switch ($data['type']) {
            case LevelLeaderboardFetchType::FRIENDS:
                if (empty($request->account)) {
                    throw new GeometryDashChineseServerException(__('gdcn.game.error.level_leaderboard_fetch_failed_need_login'), game_response: Response::GAME_LEVEL_LEADERBOARD_FETCH_FAILED_NEED_LOGIN->value);
                }

                $query->whereIn(
                    'account_id',
                    $request->account->friends()
                        ->with('account', 'friend_account')
                        ->get()
                        ->map(function (AccountFriend $friend) use ($request) {
                            return ($friend->account->is($request->account) ? $friend->friend_account : $friend->account)->id;
                        })->push($request->account->id)
                );

                $query->orderByDesc('percent');
                $query->orderByDesc('coins');
                break;
            case LevelLeaderboardFetchType::TOP:
                $query->orderByDesc('percent');
                $query->orderByDesc('coins');
                break;
            case LevelLeaderboardFetchType::WEEK:
                $query->where('created_at', '>=', now()->subWeek());
                $query->orderByDesc('percent');
                $query->orderByDesc('coins');
                break;
            default:
                throw new GeometryDashChineseServerException(__('gdcn.game.error.level_leaderboard_fetch_failed_invalid_mode'), game_response: Response::GAME_LEVEL_LEADERBOARD_FETCH_FAILED_INVALID_MODE->value);
        }

        return $query->get()
            ->map(function (LevelScore $score) use (&$top) {
                return ObjectService::merge([
                    LeaderboardObject::USER_NAME => $score->account->user->name,
                    LeaderboardObject::USER_ID => $score->account->user->id,
                    LeaderboardObject::PERCENT => $score->percent,
                    LeaderboardObject::RANKING => ++$top,
                    LeaderboardObject::USER_ICON_ID => $score->account->user->score->icon,
                    LeaderboardObject::USER_COLOR_ID => $score->account->user->score->color1,
                    LeaderboardObject::USER_SECOND_COLOR_ID => $score->account->user->score->color2,
                    LeaderboardObject::COINS => $score->coins,
                    LeaderboardObject::USER_ICON_TYPE => $score->account->user->score->icon_type,
                    LeaderboardObject::USER_SPECIAL => $score->account->user->score->special,
                    LeaderboardObject::USER_UUID => $score->account->user->uuid,
                    LeaderboardObject::AGE => $score->created_at
                        ?->locale('en')
                        ->diffForHumans(syntax: true),
                ], ':');
            })->join('|');
    }

    public function update(LevelLeaderboardUploadAndFetchRequest $request): void
    {
        $data = $request->validated();

        if ($data['percent'] <= 0) {
            return;
        }

        LevelScore::query()
            ->updateOrCreate([
                'account_id' => $data['accountID'],
                'level_id' => $data['levelID'],
            ], [
                'attempts' => $data['s8'],
                'percent' => $data['percent'],
                'coins' => $data['s9'] - 5819
            ]);
    }
}
