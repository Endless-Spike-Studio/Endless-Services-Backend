<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\Response;
use App\Exceptions\GDCS\AccountCommentCreateException;
use App\Exceptions\GDCS\AccountCommentNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountCommentCreateRequest;
use App\Http\Requests\GDCS\AccountCommentDeleteRequest;
use App\Http\Requests\GDCS\AccountCommentFetchRequest;
use App\Models\GDCS\AccountComment;
use App\Services\GDCS\AccountCommentService;

class AccountCommentController extends Controller
{
    public function __construct(
        protected AccountCommentService $service
    )
    {
    }

    public function create(AccountCommentCreateRequest $request): int|string
    {
        try {
            $data = $request->validated();
            $comment = $this->service->create($data['accountID'], $data['comment']);

            if ($comment instanceof AccountComment) {
                return $comment->id;
            }

            return $comment;
        } catch (AccountCommentCreateException $e) {
            return $e->getMessage() ?: Response::ACCOUNT_COMMENT_CREATE_FAILED->value;
        }
    }

    public function index(AccountCommentFetchRequest $request): string
    {
        try {
            $data = $request->validated();
            return $this->service->index($data['accountID'], $data['page']);
        } catch (AccountCommentNotFoundException) {
            return \App\Enums\Response::empty();
        }
    }

    public function delete(AccountCommentDeleteRequest $request): int
    {
        $data = $request->validated();

        return $this->service->delete($data['accountID'], $data['commentID'])
            ? \App\Enums\Response::ACCOUNT_COMMENT_DELETE_SUCCESS->value
            : \App\Enums\Response::ACCOUNT_COMMENT_DELETE_FAILED->value;
    }
}
