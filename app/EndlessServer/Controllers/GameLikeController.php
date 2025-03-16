<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\AccountComment;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\LevelComment;
use App\EndlessServer\Models\LevelList;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Requests\GameItemLikeRequest;
use App\GeometryDash\Enums\GeometryDashLikeTypes;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Support\Facades\Auth;

readonly class GameLikeController
{
	public function forItem(GameItemLikeRequest $request): int
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		switch ($data['type']) {
			case GeometryDashLikeTypes::LEVEL->value:
				$level = Level::query()
					->where('id', $data['itemID'])
					->first();

				if ($level === null) {
					return GeometryDashResponses::ITEM_LIKE_FAILED_LEVEL_NOT_FOUND->value;
				}

				$query = $level->likeRecords();
				break;
			case GeometryDashLikeTypes::LEVEL_COMMENT->value:
				$levelComment = LevelComment::query()
					->where('id', $data['itemID'])
					->first();

				if ($levelComment === null) {
					return GeometryDashResponses::ITEM_LIKE_FAILED_LEVEL_COMMENT_NOT_FOUND->value;
				}

				$query = $levelComment->likeRecords();
				break;
			case GeometryDashLikeTypes::ACCOUNT_COMMENT->value:
				$accountComment = AccountComment::query()
					->where('id', $data['itemID'])
					->first();

				if ($accountComment === null) {
					return GeometryDashResponses::ITEM_LIKE_FAILED_ACCOUNT_COMMENT_NOT_FOUND->value;
				}

				$query = $accountComment->likeRecords();
				break;
			case GeometryDashLikeTypes::LEVEL_LIST->value:
				$levelList = LevelList::query()
					->where('id', $data['itemID'])
					->first();

				if ($levelList === null) {
					return GeometryDashResponses::ITEM_LIKE_FAILED_LEVEL_LIST_NOT_FOUND->value;
				}

				$query = $levelList->likeRecords();
				break;
		}

		if (!isset($query)) {
			return GeometryDashResponses::ITEM_LIKE_FAILED_INVALID_LIKE_TYPE->value;
		}

		if (isset($data['like']) && $data['like'] > 0) {
			$query->updateOrCreate([
				'player_id' => $player->id
			]);
		} else {
			$query->where('player_id', $player->id)
				->delete();
		}

		return GeometryDashResponses::ITEM_LIKE_SUCCESS->value;
	}
}