<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\Response;
use App\Exceptions\GDCS\GameException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountCommentCreateRequest;
use App\Http\Requests\GDCS\AccountCommentDeleteRequest;
use App\Http\Requests\GDCS\AccountCommentFetchRequest;
use App\Http\Traits\GameLog;
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
            $this->logGame(__('messages.game.execute_account_comment_command'), [
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

        $this->logGame(__('messages.game.create_account_comment'), [
            'comment_id' => $comment->id
        ]);

        return $comment->id;
    }

    /**
     * @throws GameException
     */
    public function index(AccountCommentFetchRequest $request): string
    {
        $data = $request->validated();
        $account = $request->account;
        $account->loadCount('comments');

        if ($account->comments_count <= 0) {
            throw new GameException(
                __('error.game.account.comment.empty'),
                response_code: Response::empty()
            );
        }

        $this->logGame(__('messages.game.index_account_comment'));

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
     * @throws GameException
     */
    public function delete(AccountCommentDeleteRequest $request): int
    {
        $data = $request->validated();
        $account = $request->account;

        $query = $account->comments()
            ->whereKey($data['commentID']);

        if (!$query->exists()) {
            throw new GameException(__('error.game.account.comment.not_found'), response_code: Response::GAME_ACCOUNT_COMMENT_DELETE_FAILED_NOT_FOUND->value);
        }

        $query->delete();
        $this->logGame(__('messages.game.delete_account_comment'));

        return Response::GAME_ACCOUNT_COMMENT_DELETE_SUCCESS->value;
    }
}
