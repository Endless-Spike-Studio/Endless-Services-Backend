<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountComment;
use App\EndlessServer\Requests\GameAccountCommentDeleteRequest;
use App\EndlessServer\Requests\GameAccountCommentListRequest;
use App\EndlessServer\Requests\GameAccountCommentUploadRequest;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\Objects\GeometryDashCommentObjectDefinitions;
use App\GeometryDash\Services\GeometryDashObjectService;
use Base64Url\Base64Url;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class GameAccountCommentController
{
	public function __construct(
		protected readonly GeometryDashObjectService $objectService
	)
	{

	}

	public function upload(GameAccountCommentUploadRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$account->comments()
			->create([
				'content' => Base64Url::decode($data['comment'])
			]);

		return GeometryDashResponses::ACCOUNT_COMMENT_UPLOAD_SUCCESS->value;
	}

	public function list(GameAccountCommentListRequest $request): string
	{
		$data = $request->validated();

		Carbon::setLocale('en');

		return AccountComment::query()
			->forPage($data['page'])
			->get()
			->map(function (AccountComment $comment) {
				return $this->objectService->merge([
					GeometryDashCommentObjectDefinitions::CONTENT->value => Base64Url::encode($comment->content, true),
					GeometryDashCommentObjectDefinitions::LIKES->value => 0, //TODO,
					GeometryDashCommentObjectDefinitions::ID->value => $comment->id,
					GeometryDashCommentObjectDefinitions::IS_SPAM->value => $comment->spam,
					GeometryDashCommentObjectDefinitions::AGE->value => $comment->created_at->diffForHumans(syntax: true)
				], GeometryDashCommentObjectDefinitions::GLUE);
			})->join('|');
	}

	public function delete(GameAccountCommentDeleteRequest $request): int
	{
		$data = $request->validated();

		$comment = AccountComment::query()
			->where('id', $data['commentID'])
			->first();

		if (empty($comment)) {
			return GeometryDashResponses::ACCOUNT_COMMENT_DELETE_FAILED_NOT_FOUND->value;
		}

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		if ($account->isNot($comment->account)) {
			return GeometryDashResponses::ACCOUNT_COMMENT_DELETE_FAILED_NOT_OWNER->value;
		}

		$comment->delete();

		return GeometryDashResponses::ACCOUNT_COMMENT_DELETE_SUCCESS->value;
	}
}