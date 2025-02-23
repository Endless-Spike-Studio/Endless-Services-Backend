<?php

namespace App\EndlessServer\Objects;

use App\EndlessServer\Models\AccountFriendRequest;
use App\GeometryDash\Enums\Objects\GeometryDashAccountFriendRequestObjectDefinition;
use App\GeometryDash\Objects\GameObject;
use Base64Url\Base64Url;

readonly class GameAccountFriendRequestObject extends GameObject
{
	public function __construct(
		protected AccountFriendRequest $model,
		protected bool                 $getSent
	)
	{
		parent::__construct(GeometryDashAccountFriendRequestObjectDefinition::class, GeometryDashAccountFriendRequestObjectDefinition::GLUE);
	}

	protected function properties(): array
	{
		$targetAccount = $this->targetAccount();

		return [
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_PLAYER_NAME->value => function () use ($targetAccount) {
				return $targetAccount->player->name;
			},
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_PLAYER_USER_ID->value => function () use ($targetAccount) {
				return $targetAccount->player->id;
			},
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_PLAYER_ICON_ID->value => function () use ($targetAccount) {
				return $targetAccount->player->data->icon_id;
			},
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_PLAYER_COLOR_1->value => function () use ($targetAccount) {
				return $targetAccount->player->data->color1;
			},
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_PLAYER_COLOR_2->value => function () use ($targetAccount) {
				return $targetAccount->player->data->color2;
			},
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_PLAYER_ICON_TYPE->value => function () use ($targetAccount) {
				return $targetAccount->player->data->icon_type;
			},
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_PLAYER_SPECIAL->value => function () use ($targetAccount) {
				return $targetAccount->player->data->special;
			},
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_PLAYER_UUID->value => function () use ($targetAccount) {
				return $targetAccount->player->uuid;
			},
			GeometryDashAccountFriendRequestObjectDefinition::ID->value => function () {
				return $this->model->id;
			},
			GeometryDashAccountFriendRequestObjectDefinition::COMMENT->value => function () {
				if ($this->model->comment === null) {
					return null;
				}

				return Base64Url::encode($this->model->comment, true);
			},
			GeometryDashAccountFriendRequestObjectDefinition::AGE->value => function () {
				return $this->model->created_at->diffForHumans(syntax: true);
			},
			GeometryDashAccountFriendRequestObjectDefinition::IS_NEW->value => function () {
				return !$this->model->readed;
			}
		];
	}

	protected function targetAccount()
	{
		if ($this->getSent) {
			return $this->model->targetAccount;
		}

		return $this->model->account;
	}
}