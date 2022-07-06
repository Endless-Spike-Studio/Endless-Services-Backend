<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\LevelCommentCreateRequest;
use App\Http\Requests\GDCS\LevelCommentDeleteRequest;
use App\Http\Requests\GDCS\LevelCommentFetchRequest;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelComment;
use App\Services\GDCS\LevelCommentCommandService;
use Base64Url\Base64Url;
use GDCN\GDAlgorithm\GDAlgorithm;
use GDCN\GDObject\GDObject;

class LevelCommentController extends Controller
{
    public function create(LevelCommentCreateRequest $request): int|string
    {
        $data = $request->validated();

        $ban = $request->user->ban;
        if ($ban->comment_ban) {
            return $ban->comment_ban_info;
        }

        $content = Base64Url::decode($data['comment']);
        $level = Level::query()
            ->findOrFail($data['levelID']);

        $command = app(LevelCommentCommandService::class, [
            'token' => $content,
            'account' => $request->account,
            'level' => $level,
        ]);

        if ($command->isValid()) {
            return $command->execute();
        }

        $comment = new LevelComment();
        $comment->level_id = $data['levelID'];
        $comment->account_id = $data['accountID'];
        $comment->comment = $data['comment'];
        $comment->percent = $data['percent'] ?? 0;
        $comment->save();

        return $comment->id;
    }

    public function fetchAll(LevelCommentFetchRequest $request): int|string
    {
        $data = $request->validated();
        $level = Level::query()
            ->findOrFail($data['levelID']);

        $perPage = config('gdcs.perPage', 10);
        $comments = $level->comments();
        $count = $comments->count();

        if ($count <= 0) {
            return Response::LEVEL_COMMENTS_EMPTY->value;
        }

        switch ($data['mode']) {
            case 0:
                $comments->orderByDesc('created_at');
                break;
            case 1:
                $comments->orderByDesc('likes');
                break;
            default:
                return Response::LEVEL_COMMENTS_FETCH_FAILED_INVALID_MODE->value;
        }

        return implode('#', [
            $comments->forPage(++$data['page'], $perPage)
                ->with('account.user.score')
                ->get()
                ->map(function (LevelComment $comment) {
                    return implode(':', [
                        GDObject::merge([
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
                        GDObject::merge([
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
            GDAlgorithm::genPage($data['page'], $level->comments->count(), $perPage),
        ]);
    }

    public function delete(LevelCommentDeleteRequest $request): int
    {
        $data = $request->validated();

        return Level::query()
            ->whereKey($data['levelID'])
            ->firstOrFail()
            ->comments()
            ->whereKey($data['commentID'])
            ->delete()
            ? \App\Enums\Response::LEVEL_COMMENT_DELETE_SUCCESS->value
            : Response::LEVEL_COMMENT_DELETE_FAILED->value;
    }
}
