<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\Objects\CommentObject;
use App\Enums\GDCS\Game\Objects\UserObject;
use App\Enums\GDCS\Game\Parameters\CommentMode;
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
    public function index(AccountCommentHistoryFetchRequest $request): string
    {
        $data = $request->validated();

        $comments = LevelComment::query()
            ->where('account_id', User::query()
                ->whereKey($data['userID'])
                ->value('uuid'));

        switch ($data['mode']) {
            case CommentMode::RECENT:
                $comments->orderByDesc('created_at');
                break;
            case CommentMode::MOST_LIKED:
                $comments->orderByDesc('likes');
                break;
            default:
                throw new GeometryDashChineseServerException(__('gdcn.game.error.account_comment_history_index_failed_invalid_mode'), gameResponse: Response::GAME_ACCOUNT_COMMENT_HISTORY_INDEX_FAILED_INVALID_MODE->value);
        }

        $count = $comments->count();
        if ($count <= 0) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_comment_history_index_failed_empty'), gameResponse: Response::GAME_ACCOUNT_COMMENT_HISTORY_INDEX_FAILED_EMPTY->value);
        }

        $this->logGame(__('gdcn.game.action.account_comment_history_index_success'));

        return implode('#', [
            $comments->forPage(++$data['page'], BaseGameService::$perPage)
                ->with('account.user.score')
                ->get()
                ->map(function (LevelComment $comment) {
                    return implode(':', [
                        ObjectService::merge([
                            CommentObject::LEVEL_ID => $comment->level_id,
                            CommentObject::CONTENT => $comment->comment,
                            CommentObject::USER_ID => $comment->account->user->id,
                            CommentObject::LIKES => $comment->likes,
                            CommentObject::ID => $comment->id,
                            CommentObject::IS_SPAM => $comment->spam,
                            CommentObject::AGE => $comment->created_at
                                ?->locale('en')
                                ->diffForHumans(syntax: true),
                            CommentObject::PERCENT => $comment->percent,
                            CommentObject::MOD_BADGE => $comment->account->mod_level->value,
                            CommentObject::COLOR => $comment->account->comment_color,
                        ], '~'),
                        ObjectService::merge([
                            UserObject::NAME => $comment->account->user->name,
                            UserObject::ICON_ID => $comment->account->user->score->icon,
                            UserObject::COLOR_ID => $comment->account->user->score->color1,
                            UserObject::SECOND_COLOR_ID => $comment->account->user->score->color2,
                            UserObject::ICON_TYPE => $comment->account->user->score->icon_type,
                            UserObject::SPECIAL => $comment->account->user->score->special,
                            UserObject::UUID => $comment->account->user->uuid,
                        ], '~'),
                    ]);
                })->join('|'),
            AlgorithmService::genPage($data['page'], $count),
        ]);
    }
}
