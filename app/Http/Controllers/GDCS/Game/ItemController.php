<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\Parameters\LikeType;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\ItemLikeRequest;
use App\Http\Requests\GDCS\Game\ItemRestoreRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\AccountComment;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelComment;
use App\Models\GDCS\LikeRecord;

class ItemController extends Controller
{
	use GameLog;

	/**
	 * @throws GeometryDashChineseServerException
	 */
	public function like(ItemLikeRequest $request): int
	{
		$data = $request->validated();
		$type = (int)$data['type'];

		$item = match ($type) {
			LikeType::LEVEL => Level::query()
				->find($data['itemID']),
			LikeType::LEVEL_COMMENT => LevelComment::query()
				->find($data['itemID']),
			LikeType::ACCOUNT_COMMENT => AccountComment::query()
				->find($data['itemID']),
			default => throw new GeometryDashChineseServerException(__('gdcn.game.error.item_like_failed_invalid_type'), gameResponse: Response::GAME_ITEM_LIKE_FAILED_INVALID_TYPE->value),
		};

		$record = LikeRecord::query()
			->firstOrCreate([
				'type' => $data['type'],
				'item_id' => $data['itemID'],
				'user_id' => $request->user->id
			], [
				'ip' => $request->ip()
			]);

		if (!$record->wasRecentlyCreated) {
			throw new GeometryDashChineseServerException(__('gdcn.game.error.item_like_failed_already_exists'), gameResponse: Response::GAME_ITEM_LIKE_FAILED_ALREADY_EXISTS->value);
		}

		$item->update([
			'likes' => !empty($data['like']) ? ++$item->likes : --$item->likes
		]);

		$this->logGame(__('gdcn.game.action.item_like_success'));
		return Response::GAME_ITEM_LIKE_SUCCESS->value;
	}

	public function restore(ItemRestoreRequest $request): int
	{
		$request->validated();
		$this->logGame(__('gdcn.game.action.item_restore_success'));
		return Response::GAME_ITEM_RESTORE_SUCCESS->value;
	}
}
