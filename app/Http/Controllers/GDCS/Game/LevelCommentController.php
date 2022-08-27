<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\Objects\CommentObject;
use App\Enums\GDCS\Game\Objects\UserObject;
use App\Enums\GDCS\Game\Parameters\CommentMode;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\LevelCommentCreateRequest;
use App\Http\Requests\GDCS\Game\LevelCommentDeleteRequest;
use App\Http\Requests\GDCS\Game\LevelCommentFetchRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelComment;
use App\Services\Game\AlgorithmService;
use App\Services\Game\BaseGameService;
use App\Services\Game\ObjectService;
use App\Services\GDCS\Game\Command\LevelCommentService;
use Base64Url\Base64Url;

class LevelCommentController extends Controller
{
    use GameLog;

    public function create(LevelCommentCreateRequest $request): int|string
    {
        $data = $request->validated();

        $level = Level::query()
            ->find($data['levelID']);

        $content = Base64Url::decode($data['comment']);
        $command = new LevelCommentService($content, $request->account, $level);

        if ($command->valid()) {
            $result = $command->execute();
            $this->logGame(__('gdcn.game.action.level_comment_command_execute_success'), [
                'command' => $content,
                'result' => $result
            ]);

            return $result;
        }

        $comment = $level->comments()
            ->create([
                'account_id' => $request->account->id,
                'comment' => $data['comment'],
                'percent' => $data['percent'] ?? 0
            ]);

        $this->logGame(__('gdcn.game.action.level_comment_create_success'), [
            'comment_id' => $comment->id
        ]);

        return $comment->id;
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function index(LevelCommentFetchRequest $request): int|string
    {
        $data = $request->validated();

        $level = Level::query()
            ->find($data['levelID']);

        if (!$level) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.level_comment_index_failed_level_not_found'), game_response: Response::GAME_LEVEL_COMMENT_INDEX_FAILED_LEVEL_NOT_FOUND->value);
        }

        $comments = $level->comments()
            ->with('account.user.score');

        $count = $comments->count();
        if ($count <= 0) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.level_comment_index_failed_empty'), game_response: Response::GAME_LEVEL_COMMENT_INDEX_FAILED_EMPTY->value);
        }

        match ($data['mode']) {
            CommentMode::RECENT => $comments->orderByDesc('created_at'),
            CommentMode::MOST_LIKED => $comments->orderByDesc('likes'),
            default => throw new GeometryDashChineseServerException(__('gdcn.game.error.level_comment_index_failed_invalid_mode'), game_response: Response::GAME_LEVEL_COMMENT_INDEX_FAILED_INVALID_MODE->value),
        };

        $this->logGame(__('gdcn.game.action.level_comment_index_success'));
        return implode('#', [
            $comments->forPage(++$data['page'], BaseGameService::$perPage)
                ->get()
                ->map(function (LevelComment $comment) {
                    return implode(':', [
                        ObjectService::merge([
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

    /**
     * @throws GeometryDashChineseServerException
     */
    public function delete(LevelCommentDeleteRequest $request): int
    {
        $data = $request->validated();
        $levelID = (int)$data['levelID'];

        $comment = LevelComment::query()
            ->find($data['commentID']);

        if (!$comment) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.level_comment_delete_failed_not_found'), game_response: Response::GAME_LEVEL_COMMENT_DELETE_FAILED_NOT_FOUND->value);
        }

        if ($comment->level_id !== $levelID) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.level_comment_delete_failed_parameter_validate_failed'), game_response: Response::GAME_LEVEL_COMMENT_DELETE_FAILED_PARAMETER_VALIDATE_FAILED->value);
        }

        if ($comment->account->isNot($request->account)) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.level_comment_delete_failed_not_owner'), game_response: Response::GAME_LEVEL_COMMENT_DELETE_FAILED_NOT_OWNER->value);
        }

        $comment->delete();
        $this->logGame(__('gdcn.game.action.level_comment_delete_success'));

        return Response::GAME_LEVEL_COMMENT_DELETE_SUCCESS->value;
    }
}
