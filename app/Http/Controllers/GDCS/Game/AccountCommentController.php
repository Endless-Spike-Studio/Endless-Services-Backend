<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\AccountCommentCreateRequest;
use App\Http\Requests\GDCS\Game\AccountCommentDeleteRequest;
use App\Http\Requests\GDCS\Game\AccountCommentFetchRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountComment;
use App\Services\Game\AlgorithmService;
use App\Services\Game\BaseGameService;
use App\Services\Game\ObjectService;
use App\Services\GDCS\Game\Command\AccountCommandService;
use Base64Url\Base64Url;

class AccountCommentController extends Controller
{
    use GameLog;

    public function create(AccountCommentCreateRequest $request): int|string
    {
        $data = $request->validated();

        $content = Base64Url::decode($data['comment']);
        $command = new AccountCommandService($content, $request->account);

        if ($command->valid()) {
            $result = $command->execute();
            $this->logGame(__('gdcn.game.action.account_comment_command_execute_success'), [
                'command' => $content,
                'result' => $result
            ]);

            return $result;
        }

        $comment = $request->account
            ->comments()
            ->create([
                'comment' => $data['comment']
            ]);

        $this->logGame(__('gdcn.game.action.account_comment_create_success'), [
            'comment_id' => $comment->id
        ]);

        return $comment->id;
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function index(AccountCommentFetchRequest $request): string
    {
        $data = $request->validated();
        $account = Account::find($data['accountID']);

        if (!$account) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_comment_index_failed_target_not_found'), response: Response::GAME_ACCOUNT_COMMENT_INDEX_FAILED_TARGET_NOT_FOUND->value);
        }

        $account->loadCount('comments');
        if ($account->comments_count <= 0) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_comment_index_failed_empty'), response: Response::GAME_ACCOUNT_COMMENT_INDEX_FAILED_EMPTY->value);
        }

        $this->logGame(__('gdcn.game.action.account_comment_index_success'));

        return implode('#', [
            $account->comments()
                ->forPage(++$data['page'], BaseGameService::$perPage)
                ->get()
                ->map(function (AccountComment $comment) {
                    return ObjectService::merge([
                        2 => $comment->comment,
                        4 => $comment->likes,
                        6 => $comment->id,
                        7 => $comment->spam,
                        9 => $comment->created_at
                            ?->locale('en')
                            ->diffForHumans(syntax: true),
                    ], '~');
                })->join('|'),
            AlgorithmService::genPage($data['page'], $account->comments_count)
        ]);
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function delete(AccountCommentDeleteRequest $request): int
    {
        $data = $request->validated();
        $comment = AccountComment::find($data['commentID']);

        if (!$comment) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_comment_delete_failed_not_found'), response: Response::GAME_ACCOUNT_COMMENT_DELETE_FAILED_NOT_FOUND->value);
        }

        if (!$comment->account->isNot($request->account)) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_comment_delete_failed_not_owner'), response: Response::GAME_ACCOUNT_COMMENT_DELETE_FAILED_NOT_OWNER->value);
        }

        $comment->delete();
        $this->logGame(__('gdcn.game.action.account_comment_delete_success'));

        return Response::GAME_ACCOUNT_COMMENT_DELETE_SUCCESS->value;
    }
}
