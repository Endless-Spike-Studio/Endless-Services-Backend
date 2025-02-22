<?php

namespace App\EndlessServer\Responses;

use App\EndlessServer\Models\AccountMessage;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Models\PlayerData;
use App\EndlessServer\Repositories\AccountFriendRepository;
use App\GeometryDash\Enums\GeometryDashFriendStates;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\Objects\GeometryDashPlayerInfoObjectDefinitions;
use App\GeometryDash\Services\GeometryDashObjectService;
use Base64Url\Base64Url;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Carbon;

readonly class GamePlayerInfoObjectResponse implements Responsable
{
	public function __construct(
		protected Player  $model,
		protected ?Player $viewer = null
	)
	{
		Carbon::setLocale('en');
	}

	public function toResponse($request)
	{
		$inComingFriendRequest = null;

		$friendState = GeometryDashFriendStates::NONE->value;

		if ($this->viewer !== null && $this->model->account !== null && $this->viewer->account !== null) {
			$blocked = $this->model->account->blocklist()
				->where('target_account_id', $this->viewer->account->id)
				->exists();

			if ($blocked) {
				return GeometryDashResponses::PLAYER_INFO_FETCH_FAILED_BLOCKED->value;
			}

			$friend = app(AccountFriendRepository::class)
				->createQueryByAccountIdAndTargetAccountId($this->viewer->account->id, $this->model->account->id)
				->first();

			if ($friend !== null) {
				$friendState = GeometryDashFriendStates::ALREADY->value;
			}

			$outComingFriendRequest = $this->viewer->account->friendRequests()
				->where('target_account_id', $this->model->account->id)
				->first();

			if ($outComingFriendRequest !== null) {
				$friendState = GeometryDashFriendStates::SEND->value;
			}

			$inComingFriendRequest = $this->model->account->receiveFriendRequests()
				->where('account_id', $this->model->account->id)
				->first();

			if ($inComingFriendRequest !== null) {
				$friendState = GeometryDashFriendStates::RECEIVED->value;
			}
		}

		return app(GeometryDashObjectService::class)->merge([
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_NAME->value => $this->model->name,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_ID->value => $this->model->id,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_STARS->value => $this->model->data->stars,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_DEMONS->value => $this->model->data->demons,
			GeometryDashPlayerInfoObjectDefinitions::RANKING->value => PlayerData::query()
				->where('stars', '<=', $this->model->data->stars)
				->count(),
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_HIGHLIGHT->value => PlayerData::query()
				->where('stars', '<=', $this->model->data->stars)
				->count(),
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_CREATOR_POINTS->value => $this->model->statistic->creator_points,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_ICON_ID->value => $this->model->data->icon_id,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COLOR_1->value => $this->model->data->color1,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COLOR_2->value => $this->model->data->color2,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COINS->value => $this->model->data->coins,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_ICON_TYPE->value => $this->model->data->icon_type,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_SPECIAL->value => $this->model->data->special,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_UUID->value => $this->model->uuid,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_USER_COINS->value => $this->model->data->user_coins,
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_MESSAGE_STATE->value => $this->model->account->setting->message_state->value,
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_FRIEND_REQUEST_STATE->value => $this->model->account->setting->friend_request_state->value,
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_YOUTUBE->value => $this->model->account->setting->youtube,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_CUBE_ID->value => $this->model->data->cube_id,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_SHIP_IP->value => $this->model->data->ship_id,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_BALL_ID->value => $this->model->data->ball_id,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_BIRD_ID->value => $this->model->data->ball_id,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_WAVE_ID->value => $this->model->data->dart_id,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_ROBOT_ID->value => $this->model->data->robot_id,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_GLOW_ID->value => $this->model->data->glow_id,
			GeometryDashPlayerInfoObjectDefinitions::IS_REGISTERED->value => true,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_GLOBAL_RANK->value => PlayerData::query()
				->where('stars', '<=', $this->model->data->stars)
				->count(),
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_FRIEND_STATE->value => $friendState,
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_IN_COMING_FRIEND_REQUEST_ID->value => value(function () use ($inComingFriendRequest) {
				if ($inComingFriendRequest === null) {
					return null;
				}

				return $inComingFriendRequest->id;
			}),
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_IN_COMING_FRIEND_REQUEST_COMMENT->value => value(function () use ($inComingFriendRequest) {
				if ($inComingFriendRequest === null) {
					return null;
				}

				$comment = null;

				if ($inComingFriendRequest->comment !== null) {
					$comment = Base64Url::encode($inComingFriendRequest->comment, true);
				}

				return $comment;
			}),
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_IN_COMING_FRIEND_REQUEST_AGE->value => value(function () use ($inComingFriendRequest) {
				if ($inComingFriendRequest === null) {
					return null;
				}

				return $inComingFriendRequest->created_at->diffForHumans(syntax: true);
			}),
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_NEW_MESSAGE_COUNT->value => value(function () {
				if ($this->model->account === null) {
					return 0;
				}

				return AccountMessage::query()
					->where('target_account_id', $this->model->account->id)
					->where('readed', false)
					->count();
			}),
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_NEW_FRIEND_REQUEST_COUNT->value => value(function () {
				if ($this->model->account === null) {
					return 0;
				}

				return $this->model->account->receiveFriendRequests()
					->where('readed', false)
					->count();
			}),
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_NEW_FRIEND_COUNT->value => value(function () {
				if ($this->model->account === null) {
					return 0;
				}

				return $this->model->account->friends()
					->where('readed', false)
					->count();
			}),
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_HAS_NEW_FRIEND_REQUEST->value => value(function () {
				if ($this->model->account === null) {
					return false;
				}

				return $this->model->account->receiveFriendRequests()
					->where('readed', false)
					->exists();
			}),
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_SPIDER_ID->value => $this->model->data->spider_id,
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_TWITTER->value => $this->model->account->setting->twitter,
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_TWITCH->value => $this->model->account->setting->twitch,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_DIAMONDS->value => $this->model->data->diamonds,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_EXPLOSION_ID->value => $this->model->data->explosion_id,
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_MOD_LEVEL->value => value(function () {
				if ($this->model->account === null) {
					return null;
				}

				return $this->model->account->mod_level;
			}),
			GeometryDashPlayerInfoObjectDefinitions::ACCOUNT_COMMENT_HISTORY_STATE->value => $this->model->account->setting->comment_history_state->value,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COLOR_3->value => $this->model->data->color3,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_MOONS->value => $this->model->data->moons,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_SWING_ID->value => $this->model->data->swing_id,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_JETPACK_ID->value => $this->model->data->jetpack_id,
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COMPLETED_DEMONS_INFO->value => collect([
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
			])->join(','),
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COMPLETED_CLASSIC_LEVELS_INFO->value => collect([
				$this->model->statistic->completed_classic_auto_count,
				$this->model->statistic->completed_classic_easy_count,
				$this->model->statistic->completed_classic_normal_count,
				$this->model->statistic->completed_classic_hard_count,
				$this->model->statistic->completed_classic_harder_count,
				$this->model->statistic->completed_classic_insane_count,
				$this->model->statistic->completed_dailies_count,
				$this->model->statistic->completed_gauntlet_levels_count
			])->join(','),
			GeometryDashPlayerInfoObjectDefinitions::PLAYER_COMPLETED_PLATFORMER_LEVELS_INFO->value => collect([
				$this->model->statistic->completed_platformer_auto_count,
				$this->model->statistic->completed_platformer_easy_count,
				$this->model->statistic->completed_platformer_normal_count,
				$this->model->statistic->completed_platformer_hard_count,
				$this->model->statistic->completed_platformer_harder_count,
				$this->model->statistic->completed_platformer_insane_count
			])->join(',')
		], GeometryDashPlayerInfoObjectDefinitions::GLUE);
	}
}