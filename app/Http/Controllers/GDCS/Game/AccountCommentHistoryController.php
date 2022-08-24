<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\CommentMode;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\AccountCommentHistoryFetchRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\LevelComment;
use App\Models\GDCS\User;
use App\Services\Game\AlgorithmService;
use App\Services\Game\BaseGameService;
use App\Services\Game\ObjectService;

class AccountCommentHistoryController extends Controller
{
    use GameLog;

    /**
     * @throws GeometryDashChineseServerException
     */
    public function index(AccountCommentHistoryFetchRequest $request): int|string
    {
        $data = $request->validated();

        $comments = LevelComment::query()
            ->where('account_id', User::query()
                ->where('id', $data['userID'])
                ->value('uuid'));

        $count = $comments->count();
        if ($count <= 0) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_comment_history_index_failed_empty'), Response::GAME_ACCOUNT_COMMENT_HISTORY_INDEX_FAILED_EMPTY->value);
        }

        switch ($data['mode']) {
            case CommentMode::RECENT->value:
                $comments->orderByDesc('created_at');
                break;
            case CommentMode::MOST_LIKED->value:
                $comments->orderByDesc('likes');
                break;
            default:
                throw new GeometryDashChineseServerException(__('gdcn.game.error.account_comment_history_index_failed_invalid_mode'), Response::GAME_ACCOUNT_COMMENT_HISTORY_INDEX_FAILED_INVALID_MODE->value);
        }

        $this->logGame(__('gdcn.game.action.account_comment_history_index_success'));

        return implode('#', [
            $comments->forPage(++$data['page'], BaseGameService::$perPage)
                ->with('account.user.score')
                ->get()
                ->map(function (LevelComment $comment) {
                    return implode(':', [
                        ObjectService::merge([
                            1 => $comment->level_id,
                            2 => $comment->comment,
                            3 => $comment->account->user->id,
                            4 => $comment->likes,
                            6 => $comment->id,
                            7 => $comment->spam,
                            9 => $comment->created_at
                                ?->locale('en')
                                ->diffForHumans(syntax: true),
                            10 => $comment->percent,
                            11 => $comment->account->mod_level->value,
                            12 => $comment->account->comment_color,
                        ], '~'),
                        ObjectService::merge([
                            1 => $comment->account->name,
                            9 => $comment->account->user->score->icon,
                            10 => $comment->account->user->score->color1,
                            11 => $comment->account->user->score->color2,
                            14 => $comment->account->user->score->icon_type,
                            15 => $comment->account->user->score->acc_glow,
                            16 => $comment->account->id,
                        ], '~'),
                    ]);
                })->join('|'),
            AlgorithmService::genPage($data['page'], $count),
        ]);
    }
}
