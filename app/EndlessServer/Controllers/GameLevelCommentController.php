<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\LevelComment;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Objects\GameCommentObject;
use App\EndlessServer\Objects\GamePlayerInfoObject;
use App\EndlessServer\Requests\GameAccountSentLevelCommentHistoryListRequest;
use App\EndlessServer\Requests\GameLevelCommentDeleteRequest;
use App\EndlessServer\Requests\GameLevelCommentListRequest;
use App\EndlessServer\Requests\GameLevelCommentUploadRequest;
use App\EndlessServer\Services\GamePaginationService;
use App\GeometryDash\Enums\GeometryDashLevelCommentModes;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\Objects\GeometryDashCommentObjectDefinitions;
use App\GeometryDash\Enums\Objects\GeometryDashPlayerInfoObjectDefinitions;
use Base64Url\Base64Url;
use Illuminate\Support\Facades\Auth;

readonly class GameLevelCommentController
{
	public function __construct(
		protected GamePaginationService $paginationService
	)
	{

	}

	public function upload(GameLevelCommentUploadRequest $request): int
	{
		$data = $request->validated();

		if (!isset($data['percent'])) {
			$data['percent'] = null;
		}

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		LevelComment::query()
			->create([
				'account_id' => $account->id,
				'level_id' => $data['levelID'],
				'content' => Base64Url::decode($data['comment']),
				'spam' => false,
				'percent' => $data['percent']
			]);

		return GeometryDashResponses::LEVEL_COMMENT_UPLOAD_SUCCESS->value;
	}

	public function list(GameLevelCommentListRequest $request): string
	{
		$data = $request->validated();

		$level = Level::query()
			->where('id', $data['levelID'])
			->first();

		$query = $level->comments();

		switch ($data['mode']) {
			case GeometryDashLevelCommentModes::RECENT:
				$query->latest();
				break;
			case GeometryDashLevelCommentModes::MOST_LIKED:
				$query->withCount('likeRecords');
				$query->orderByDesc('like_records_count');
				break;
		}

		$paginate = $this->paginationService->generate($query, $data['page']);

		return implode(GeometryDashCommentObjectDefinitions::SEGMENTATION, [
			$paginate->items->map(function (LevelComment $comment) use ($request) {
				return implode(':', [
					new GameCommentObject($comment)->except([
						GeometryDashCommentObjectDefinitions::LEVEL_ID->value
					])->merge(),
					new GamePlayerInfoObject($comment->account->player)->only([
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_NAME->value,
						GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_HIGHLIGHT->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_ICON_ID->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_COLOR_1->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_COLOR_2->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_ICON_TYPE->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_SPECIAL->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_UUID->value
					])->merge(GeometryDashCommentObjectDefinitions::GLUE)
				]);
			})->join(GeometryDashCommentObjectDefinitions::SEPARATOR),
			$paginate->info()
		]);
	}

	public function history(GameAccountSentLevelCommentHistoryListRequest $request): string
	{
		$data = $request->validated();

		$player = Player::query()
			->where('id', $data['userID'])
			->first();

		$query = LevelComment::query()
			->where('account_id', $player->account->id);

		switch ($data['mode']) {
			case GeometryDashLevelCommentModes::RECENT:
				$query->latest();
				break;
			case GeometryDashLevelCommentModes::MOST_LIKED:
				$query->withCount('likeRecords');
				$query->orderByDesc('like_records_count');
				break;
		}

		$paginate = $this->paginationService->generate($query, $data['page']);

		return implode(GeometryDashCommentObjectDefinitions::SEGMENTATION, [
			$paginate->items->map(function (LevelComment $comment) use ($request) {
				return implode(':', [
					new GameCommentObject($comment)->merge(),
					new GamePlayerInfoObject($comment->account->player)->only([
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_NAME->value,
						GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_HIGHLIGHT->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_ICON_ID->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_COLOR_1->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_COLOR_2->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_ICON_TYPE->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_SPECIAL->value,
						GeometryDashPlayerInfoObjectDefinitions::PLAYER_UUID->value
					])->merge(GeometryDashCommentObjectDefinitions::GLUE)
				]);
			})->join(GeometryDashCommentObjectDefinitions::SEPARATOR),
			$paginate->info()
		]);
	}

	public function delete(GameLevelCommentDeleteRequest $request)
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		LevelComment::query()
			->where('id', $data['commentID'])
			->where('level_id', $data['levelID'])
			->where('account_id', $account->id)
			->delete();

		return GeometryDashResponses::LEVEL_COMMENT_DELETE_SUCCESS->value;
	}
}