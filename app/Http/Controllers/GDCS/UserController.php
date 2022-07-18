<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\UserFetchRequest;
use App\Http\Requests\GDCS\UserSearchRequest;
use App\Models\GDCS\AccountBlock;
use App\Models\GDCS\AccountFriend;
use App\Models\GDCS\User;
use App\Models\GDCS\UserScore;
use GeometryDashChinese\GeometryDashAlgorithm;
use GeometryDashChinese\GeometryDashObject;

class UserController extends Controller
{
    public function search(UserSearchRequest $request): int|string
    {
        $data = $request->validated();
        $perPage = config('gdcs.perPage', 10);
        $query = User::query()
            ->whereKey($data['str'])
            ->orWhere('name', 'LIKE', $data['str'] . '%');

        $count = $query->count();
        if ($count <= 0) {
            return Response::USER_FETCH_EMPTY_RESULT->value;
        }

        return implode('#', [
            $query->forPage(++$data['page'], $perPage)
                ->with('score', 'account')
                ->get()
                ->map(function (User $user) {
                    return GeometryDashObject::merge([
                        1 => $user->name,
                        2 => $user->id,
                        3 => $user->score->stars,
                        4 => $user->score->demons,
                        6 => UserScore::query()
                            ->where('stars', '<=', $user->score->stars)
                            ->count(),
                        8 => $user->score->creator_points,
                        9 => $user->score->icon,
                        10 => $user->score->color1,
                        11 => $user->score->color2,
                        13 => $user->score->coins,
                        14 => $user->score->icon_type,
                        15 => $user->score->special,
                        16 => $user->uuid,
                        17 => $user->score->user_coins,
                    ], ':');
                })->join('|'),
            GeometryDashAlgorithm::genPage($data['page'], $count, $perPage),
        ]);
    }

    public function fetchAll(UserFetchRequest $request): int|string
    {
        $data = $request->validated();

        switch ($data['type']) {
            case 0:
                $query = $request->account->friends();
                break;
            case 1:
                $query = $request->account->blocks();
                break;
            default:
                return Response::USER_FETCH_FAILED_INVALID_TYPE->value;
        }

        if ($query->count() <= 0) {
            return Response::USER_FETCH_EMPTY_RESULT->value;
        }

        return $query->get()
            ->map(function (AccountFriend|AccountBlock $item) use ($data) {
                $accountID = (int)$data['accountID'];

                if ($item instanceof AccountFriend) {
                    if ($item->account_id === $accountID) {
                        $account = $item->friend_account;
                        $isNew = $item->new;
                        $item->new = false;
                    } else {
                        $account = $item->account;
                        $isNew = $item->friend_new;
                        $item->friend_new = false;
                    }

                    $item->save();
                } else {
                    $isNew = false;
                    $account = $item->account_id === $accountID ? $item->target_account : $item->account;
                }

                return GeometryDashObject::merge([
                    1 => $account->user->name,
                    2 => $account->user->id,
                    9 => $account->user->score->icon,
                    10 => $account->user->score->color1,
                    11 => $account->user->score->color2,
                    14 => $account->user->score->icon_type,
                    15 => $account->user->score->special,
                    16 => $account->user->uuid,
                    18 => $account->setting->message_state,
                    41 => $isNew,
                ], ':');
            })->join('|');
    }
}
