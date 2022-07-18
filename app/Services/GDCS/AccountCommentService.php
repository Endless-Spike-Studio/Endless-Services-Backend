<?php

namespace App\Services\GDCS;

use App\Exceptions\GDCS\AccountCommentCommandExecuteException;
use App\Exceptions\GDCS\AccountCommentCreateException;
use App\Exceptions\GDCS\AccountCommentNotFoundException;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountComment;
use Base64Url\Base64Url;
use GeometryDashChinese\GeometryDashAlgorithm;
use GeometryDashChinese\GeometryDashObject;

class AccountCommentService
{
    /**
     * @throws AccountCommentCreateException
     */
    public function create(int $accountID, string $content): int|string|AccountComment
    {
        $account = Account::query()
            ->findOrFail($accountID);

        $this->checkCreatePermission($account);

        try {
            return $this->tryExecuteCommand($account, $content);
        } catch (AccountCommentCommandExecuteException) {
            return $account->comments()
                ->create([
                    'comment' => $content,
                ]);
        }
    }

    /**
     * @throws AccountCommentCreateException
     */
    protected function checkCreatePermission(Account $account): void
    {
        if ($account->user->ban->comment_ban) {
            throw new AccountCommentCreateException($account->user->ban->comment_ban_info);
        }
    }

    /**
     * @throws AccountCommentCommandExecuteException
     */
    protected function tryExecuteCommand(Account $account, string $comment): ?string
    {
        $command = app(AccountCommentCommandService::class, [
            'token' => Base64Url::decode($comment),
            'account' => $account,
        ]);

        if ($command->isValid()) {
            return $command->execute();
        }

        throw new AccountCommentCommandExecuteException();
    }

    /**
     * @throws AccountCommentNotFoundException
     */
    public function index(int $accountID, int $page = 0): string
    {
        $account = Account::query()
            ->findOrFail($accountID)
            ?->loadCount('comments');

        if ($account->comments_count <= 0) {
            throw new AccountCommentNotFoundException();
        }

        return implode('#', [
            $account->comments()
                ->forPage(
                    ++$page,
                    config('gdcs.perPage', 10)
                )
                ->get()
                ->map(function (AccountComment $comment) {
                    return GeometryDashObject::merge([
                        2 => $comment->comment,
                        4 => $comment->likes,
                        6 => $comment->id,
                        7 => $comment->spam,
                        9 => $comment->created_at
                            ?->locale('en')
                            ->diffForHumans(syntax: true),
                    ], '~');
                })->join('|'),
            GeometryDashAlgorithm::genPage($page, $account->comments_count, config('gdcs.perPage', 10)),
        ]);
    }

    public function delete(int $accountID, int $commentID): bool
    {
        return Account::query()
            ->findOrFail($accountID)
            ?->comments()
            ->whereKey($commentID)
            ->delete();
    }
}
