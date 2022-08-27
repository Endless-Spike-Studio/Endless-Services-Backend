<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\Objects\UserObject;
use App\Enums\GDCS\Game\UserIndexType;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\UserFetchRequest;
use App\Http\Requests\GDCS\Game\UserSearchRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\AccountBlock;
use App\Models\GDCS\AccountFriend;
use App\Models\GDCS\User;
use App\Models\GDCS\UserScore;
use App\Services\Game\AlgorithmService;
use App\Services\Game\BaseGameService;
use App\Services\Game\ObjectService;

class UserController extends Controller
{
    use GameLog;

    /**
     * @throws GeometryDashChineseServerException
     */
    public function search(UserSearchRequest $request): string
    {
        $data = $request->validated();

        $query = User::query()
            ->whereKey($data['str'])
            ->orWhere('name', 'LIKE', $data['str'] . '%')
            ->with('score', 'account');

        $count = $query->count();
        if ($count <= 0) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.user_search_failed_empty'), game_response: Response::GAME_USER_SEARCH_FAILED_EMPTY->value);
        }

        $this->logGame(__('gdcn.game.action.user_search_success'));
        return implode('#', [
            $query->forPage(++$data['page'], BaseGameService::$perPage)
                ->get()
                ->map(function (User $user) {
                    return ObjectService::merge([
                        UserObject::NAME => $user->name,
                        UserObject::ID => $user->id,
                        UserObject::STARS => $user->score->stars,
                        UserObject::DEMONS => $user->score->demons,
                        UserObject::RANKING => UserScore::query()
                            ->where('stars', '<=', $user->score->stars)
                            ->count(),
                        UserObject::CREATOR_POINTS => $user->score->creator_points,
                        UserObject::ICON_ID => $user->score->icon,
                        UserObject::COLOR_ID => $user->score->color1,
                        UserObject::SECOND_COLOR_ID => $user->score->color2,
                        UserObject::COINS => $user->score->coins,
                        UserObject::ICON_TYPE => $user->score->icon_type,
                        UserObject::SPECIAL => $user->score->special,
                        UserObject::UUID => $user->uuid,
                        UserObject::USER_COINS => $user->score->user_coins,
                    ], ':');
                })->join('|'),
            AlgorithmService::genPage($data['page'], $count),
        ]);
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function index(UserFetchRequest $request): string
    {
        $data = $request->validated();
        $type = (int)$data['type'];

        $query = match ($type) {
            UserIndexType::FRIENDS->value => $request->account->friends(),
            UserIndexType::BLOCKS->value => $request->account->blocks(),
            default => throw new GeometryDashChineseServerException(__('gdcn.game.error.user_index_failed_invalid_mode'), game_response: Response::GAME_USER_INDEX_FAILED_INVALID_MODE->value),
        };

        if ($query->count() <= 0) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.user_index_failed_empty'), game_response: Response::GAME_USER_INDEX_FAILED_EMPTY->value);
        }

        $this->logGame(__('gdcn.game.action.user_index_success'));
        return $query->get()
            ->map(function (AccountFriend|AccountBlock $item) use ($request, $type) {
                $isNew = false;

                switch ($type) {
                    case UserIndexType::FRIENDS->value:
                        if ($item->account->is($request->account)) {
                            $account = $item->friend_account;
                            $isNew = $item->new;

                            $item->update([
                                'new' => false
                            ]);
                        } else {
                            $account = $item->account;
                            $isNew = $item->friend_new;

                            $item->update([
                                'friend_new' => false
                            ]);
                        }
                        break;
                    case UserIndexType::BLOCKS->value:
                        $account = $item->account->is($request->account) ? $item->target_account : $item->account;
                        break;
                    default:
                        throw new GeometryDashChineseServerException(__('gdcn.game.error.user_index_failed_invalid_mode'), game_response: Response::GAME_USER_INDEX_FAILED_INVALID_MODE->value);
                }

                return ObjectService::merge([
                    UserObject::NAME => $account->user->name,
                    UserObject::ID => $account->user->id,
                    UserObject::ICON_ID => $account->user->score->icon,
                    UserObject::COLOR_ID => $account->user->score->color1,
                    UserObject::SECOND_COLOR_ID => $account->user->score->color2,
                    UserObject::ICON_TYPE => $account->user->score->icon_type,
                    UserObject::SPECIAL => $account->user->score->special,
                    UserObject::UUID => $account->user->uuid,
                    UserObject::MESSAGE_STATE => $account->setting->message_state,
                    UserObject::IS_NEW_FRIEND_REQUEST => $isNew,
                ], ':');
            })->join('|');
    }
}
