<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountCommentHistoryFetchRequest;
use App\Models\GDCS\LevelComment;
use App\Models\GDCS\User;
use GDCN\GDAlgorithm\GDAlgorithm;
use GDCN\GDObject\GDObject;

class AccountCommentHistoryController extends Controller
{
    public function fetchAll(AccountCommentHistoryFetchRequest $request): int|string
    {
        $data = $request->validated();
        $perPage = config('gdcs.perPage', 10);

        $accountID = User::query()
            ->where('id', $data['userID'])
            ->value('uuid');

        $comments = LevelComment::query()
            ->where('account_id', $accountID);

        $count = $comments->count();
        if ($count <= 0) {
            return Response::ACCOUNT_COMMENT_HISTORY_EMPTY->value;
        }

        switch ($data['mode']) {
            case 0:
                $comments->orderByDesc('created_at');
                break;
            case 1:
                $comments->orderByDesc('likes');
                break;
            default:
                return Response::ACCOUNT_COMMENT_HISTORY_FETCH_FAILED_INVALID_MODE->value;
        }

        return implode('#', [
            $comments->forPage(++$data['page'], $perPage)
                ->with('account.user.score')
                ->get()
                ->map(function (LevelComment $comment) {
                    return implode(':', [
                        GDObject::merge([
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
                            12 => $comment->account->comment_color
                        ], '~'),
                        GDObject::merge([
                            1 => $comment->account->name,
                            9 => $comment->account->user->score->icon,
                            10 => $comment->account->user->score->color1,
                            11 => $comment->account->user->score->color2,
                            14 => $comment->account->user->score->icon_type,
                            15 => $comment->account->user->score->acc_glow,
                            16 => $comment->account->id
                        ], '~'),
                    ]);
                })->join('|'),
            GDAlgorithm::genPage($data['page'], $comments->count(), $perPage)
        ]);
    }
}
