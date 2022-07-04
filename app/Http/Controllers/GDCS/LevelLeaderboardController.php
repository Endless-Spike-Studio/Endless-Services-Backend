<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\LevelLeaderboardUploadAndFetchRequest;
use App\Models\GDCS\LevelScore;
use Carbon\Carbon;
use GDCN\GDObject\GDObject;

class LevelLeaderboardController extends Controller
{
    public function fetchAll(LevelLeaderboardUploadAndFetchRequest $request): int|string
    {
        $query = LevelScore::query();
        $this->update($request);

        $data = $request->validated();
        $query->where('level_id', $data['levelID']);

        switch ($data['type']) {
            case 0:
                $query->whereIn('account_id', $request->account->friend_account_ids_with_self);
                break;
            case 1:
                $query->orderByDesc('percent');
                break;
            case 2:
                $query->where('created_at', '>=', Carbon::now()->subWeek());
                $query->orderByDesc('percent');
                break;
            default:
                return \App\Enums\Response::LEVEL_SCORE_FETCH_FAILED_INVALID_TYPE->value;
        }

        $top = 0;
        return $query->get()
            ->map(function (LevelScore $score) use (&$top) {
                return GDObject::merge([
                    1 => $score->account->name,
                    2 => $score->account->user->id,
                    3 => $score->percent,
                    6 => ++$top,
                    9 => $score->account->user->score->icon,
                    10 => $score->account->user->score->color1,
                    11 => $score->account->user->score->color2,
                    13 => $score->coins,
                    14 => $score->account->user->score->icon_type,
                    15 => $score->account->user->score->special,
                    16 => $score->account->id,
                    42 => $score->created_at
                        ?->locale('en')
                        ->diffForHumans(syntax: true)
                ], ':');
            })->join('|');
    }

    public function update(LevelLeaderboardUploadAndFetchRequest $request): bool
    {
        $data = $request->validated();

        if ($data['percent'] <= 0) {
            return false;
        }

        $score = LevelScore::query()
            ->where([
                'account_id' => $data['accountID'],
                'level_id' => $data['levelID']
            ])->firstOrNew();

        $score->account_id = $data['accountID'];
        $score->level_id = $data['levelID'];
        $score->attempts = $data['s8'];
        $score->percent = $data['percent'];
        $score->coins = $data['s9'] - 5819;
        return $score->save();
    }
}
