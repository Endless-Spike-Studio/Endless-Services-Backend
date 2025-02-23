<?php

namespace App\EndlessServer\Objects;

use App\EndlessServer\Exceptions\EndlessServerGameException;
use App\EndlessServer\Models\AccountMessage;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Models\PlayerData;
use App\EndlessServer\Repositories\AccountFriendRepository;
use App\GeometryDash\Enums\GeometryDashFriendStates;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\Objects\GeometryDashPlayerInfoObjectDefinitions;
use App\GeometryDash\Objects\GameObject;
use Base64Url\Base64Url;

readonly class GamePlayerInfoObject extends GameObject
{
	/**
	 * @throws EndlessServerGameException
	 */
	public function __construct(
		protected Player  $model,
		protected ?Player $viewer = null
	)
	{
		parent::__construct(GeometryDashPlayerInfoObjectDefinitions::class, GeometryDashPlayerInfoObjectDefinitions::GLUE);

		$this->check();
	}

	/**
	 * @throws EndlessServerGameException
	 */
	protected function check(): void
	{
		if (!$this->isSelf()) {
			$blocked = $this->model->account->blocklist()
				->where('target_account_id', $this->viewer->account->id)
				->exists();

			if ($blocked) {
				throw new EndlessServerGameException(__('获取玩家信息失败: 被对方拉黑'), GeometryDashResponses::PLAYER_INFO_FETCH_FAILED_BLOCKED->value);
			}
		}
	}

	protected function isSelf(): bool
	{
		return $this->viewer !== null && $this->model->account !== null && $this->viewer->account !== null && $this->viewer->account->id === $this->model->account->id;
	}

	public function properties(): array
	{
		return [
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_NAME->value => function () {
				return $this->model->name;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_ID->value => function () {
				return $this->model->id;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_STARS->value => function () {
				return $this->model->data->stars;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_DEMONS->value => function () {
				return $this->model->data->demons;
			},
			GeometryDashPlayerInfoObjectDefinitions::RANKING->value => function () {
				return PlayerData::query()
					->where('stars', '<=', $this->model->data->stars)
					->count();
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_HIGHLIGHT->value => function () {
				return PlayerData::query()
					->where('stars', '<=', $this->model->data->stars)
					->count();
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_CREATOR_POINTS->value => function () {
				return $this->model->statistic->creator_points;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_ICON_ID->value => function () {
				return $this->model->data->icon_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COLOR_1->value => function () {
				return $this->model->data->color1;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COLOR_2->value => function () {
				return $this->model->data->color2;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COINS->value => function () {
				return $this->model->data->coins;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_ICON_TYPE->value => function () {
				return $this->model->data->icon_type;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_SPECIAL->value => function () {
				return $this->model->data->special;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_UUID->value => function () {
				return $this->model->uuid;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_USER_COINS->value => function () {
				return $this->model->data->user_coins;
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_MESSAGE_STATE->value => function () {
				return $this->model->account->setting->message_state->value;
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_FRIEND_REQUEST_STATE->value => function () {
				return $this->model->account->setting->friend_request_state->value;
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_YOUTUBE->value => function () {
				return $this->model->account->setting->youtube;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_CUBE_ID->value => function () {
				return $this->model->data->cube_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_SHIP_IP->value => function () {
				return $this->model->data->ship_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_BALL_ID->value => function () {
				return $this->model->data->ball_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_BIRD_ID->value => function () {
				return $this->model->data->ball_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_WAVE_ID->value => function () {
				return $this->model->data->dart_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_ROBOT_ID->value => function () {
				return $this->model->data->robot_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_GLOW_ID->value => function () {
				return $this->model->data->glow_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::IS_REGISTERED->value => function () {
				return true;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_GLOBAL_RANK->value => function () {
				return PlayerData::query()
					->where('stars', '<=', $this->model->data->stars)
					->count();
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_FRIEND_STATE->value => function () {
				if (!$this->isSelf()) {
					return null;
				}

				$friend = app(AccountFriendRepository::class)
					->createQueryByAccountIdAndTargetAccountId($this->viewer->account->id, $this->model->account->id)
					->first();

				if ($friend !== null) {
					return GeometryDashFriendStates::ALREADY->value;
				}

				$outComingFriendRequest = $this->viewer->account->friendRequests()
					->where('target_account_id', $this->model->account->id)
					->first();

				if ($outComingFriendRequest !== null) {
					return GeometryDashFriendStates::SEND->value;
				}

				$inComingFriendRequest = $this->inComingFriendRequest();

				if ($inComingFriendRequest !== null) {
					return GeometryDashFriendStates::RECEIVED->value;
				}

				return GeometryDashFriendStates::NONE->value;
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_IN_COMING_FRIEND_REQUEST_ID->value => function () {
				if (!$this->isSelf()) {
					return null;
				}

				$inComingFriendRequest = $this->inComingFriendRequest();

				if ($inComingFriendRequest === null) {
					return null;
				}

				return $inComingFriendRequest->id;
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_IN_COMING_FRIEND_REQUEST_COMMENT->value => function () {
				if (!$this->isSelf()) {
					return null;
				}

				$inComingFriendRequest = $this->inComingFriendRequest();

				if ($inComingFriendRequest === null) {
					return null;
				}

				$comment = null;

				if ($inComingFriendRequest->comment !== null) {
					$comment = Base64Url::encode($inComingFriendRequest->comment, true);
				}

				return $comment;
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_IN_COMING_FRIEND_REQUEST_AGE->value => function () {
				if (!$this->isSelf()) {
					return null;
				}

				$inComingFriendRequest = $this->inComingFriendRequest();

				if ($inComingFriendRequest === null) {
					return null;
				}

				return $inComingFriendRequest->created_at->diffForHumans(syntax: true);
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_NEW_MESSAGE_COUNT->value => function () {
				if (!$this->isSelf()) {
					return null;
				}

				if ($this->model->account === null) {
					return 0;
				}

				return AccountMessage::query()
					->where('target_account_id', $this->model->account->id)
					->where('readed', false)
					->count();
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_NEW_FRIEND_REQUEST_COUNT->value => function () {
				if (!$this->isSelf()) {
					return null;
				}

				if ($this->model->account === null) {
					return 0;
				}

				return $this->model->account->receiveFriendRequests()
					->where('readed', false)
					->count();
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_NEW_FRIEND_COUNT->value => function () {
				if (!$this->isSelf()) {
					return null;
				}

				if ($this->model->account === null) {
					return 0;
				}

				return $this->model->account->friends()
					->where('readed', false)
					->count();
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_HAS_NEW_FRIEND_REQUEST->value => function () {
				if (!$this->isSelf()) {
					return null;
				}

				if ($this->model->account === null) {
					return false;
				}

				return $this->model->account->receiveFriendRequests()
					->where('readed', false)
					->exists();
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_SPIDER_ID->value => function () {
				return $this->model->data->spider_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_TWITTER->value => function () {
				return $this->model->account->setting->twitter;
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_TWITCH->value => function () {
				return $this->model->account->setting->twitch;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_DIAMONDS->value => function () {
				return $this->model->data->diamonds;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_EXPLOSION_ID->value => function () {
				return $this->model->data->explosion_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_MOD_LEVEL->value => function () {
				if ($this->model->account === null) {
					return null;
				}

				return $this->model->account->mod_level;
			},
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_COMMENT_HISTORY_STATE->value => function () {
				return $this->model->account->setting->comment_history_state->value;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COLOR_3->value => function () {
				return $this->model->data->color3;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_MOONS->value => function () {
				return $this->model->data->moons;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_SWING_ID->value => function () {
				return $this->model->data->swing_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_JETPACK_ID->value => function () {
				return $this->model->data->jetpack_id;
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COMPLETED_DEMONS_INFO->value => function () {
				return collect([
					$this->model->statistic->completed_classic_easy_demons_count,
					$this->model->statistic->completed_classic_medium_demons_count,
					$this->model->statistic->completed_classic_hard_demons_count,
					$this->model->statistic->completed_classic_insane_demons_count,
					$this->model->statistic->completed_classic_extreme_demons_count,
					$this->model->statistic->completed_platformer_easy_demons_count,
					$this->model->statistic->completed_platformer_medium_demons_count,
					$this->model->statistic->completed_platformer_hard_demons_count,
					$this->model->statistic->completed_platformer_insane_demons_count,
					$this->model->statistic->completed_platformer_extreme_demons_count,
					$this->model->statistic->completed_weeklies_count,
					$this->model->statistic->completed_gauntlet_demon_levels_count
				])->join(',');
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COMPLETED_CLASSIC_LEVELS_INFO->value => function () {
				return collect([
					$this->model->statistic->completed_classic_auto_count,
					$this->model->statistic->completed_classic_easy_count,
					$this->model->statistic->completed_classic_normal_count,
					$this->model->statistic->completed_classic_hard_count,
					$this->model->statistic->completed_classic_harder_count,
					$this->model->statistic->completed_classic_insane_count,
					$this->model->statistic->completed_dailies_count,
					$this->model->statistic->completed_gauntlet_levels_count
				])->join(',');
			},
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COMPLETED_PLATFORMER_LEVELS_INFO->value => function () {
				return collect([
					$this->model->statistic->completed_platformer_auto_count,
					$this->model->statistic->completed_platformer_easy_count,
					$this->model->statistic->completed_platformer_normal_count,
					$this->model->statistic->completed_platformer_hard_count,
					$this->model->statistic->completed_platformer_harder_count,
					$this->model->statistic->completed_platformer_insane_count
				])->join(',');
			}
		];
	}

	protected function inComingFriendRequest()
	{
		static $inComingFriendRequest;

		if (!isset($inComingFriendRequest)) {
			$inComingFriendRequest = $this->model->account->receiveFriendRequests()
				->where('account_id', $this->model->account->id)
				->first();
		}

		return $inComingFriendRequest;
	}
}