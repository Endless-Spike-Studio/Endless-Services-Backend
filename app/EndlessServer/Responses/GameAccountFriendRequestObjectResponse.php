<?php

namespace App\EndlessServer\Responses;

use App\EndlessServer\Models\AccountFriendRequest;
use App\GeometryDash\Enums\Objects\GeometryDashAccountFriendRequestObjectDefinition;
use App\GeometryDash\Services\GeometryDashObjectService;
use Base64Url\Base64Url;
use Illuminate\Contracts\Support\Responsable;

readonly class GameAccountFriendRequestObjectResponse implements Responsable
{
	public function __construct(
		protected AccountFriendRequest $model,
		protected bool                 $getSent
	)
	{

	}

	public function toResponse($request)
	{
		$targetAccount = $this->model->account;

		if ($this->getSent) {
			$targetAccount = $this->model->targetAccount;
		}

		$comment = '';

		if ($this->model->comment !== null) {
			$comment = Base64Url::encode($this->model->comment, true);
		}

		return app(GeometryDashObjectService::class)->merge([
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_NAME->value => $targetAccount->player->name,
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_USER_ID->value => $targetAccount->player->id,
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_ICON_ID->value => $targetAccount->player->data->icon_id,
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_COLOR_ID->value => $targetAccount->player->data->color1,
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_SECOND_COLOR_ID->value => $targetAccount->player->data->color2,
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_ICON_TYPE->value => $targetAccount->player->data->icon_type,
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_SPECIAL->value => $targetAccount->player->data->special,
			GeometryDashAccountFriendRequestObjectDefinition::TARGET_UUID->value => $targetAccount->player->uuid,
			GeometryDashAccountFriendRequestObjectDefinition::ID->value => $this->model->id,
			GeometryDashAccountFriendRequestObjectDefinition::COMMENT->value => $comment,
			GeometryDashAccountFriendRequestObjectDefinition::AGE->value => $this->model->created_at->diffForHumans(syntax: true),
			GeometryDashAccountFriendRequestObjectDefinition::IS_NEW->value => !$this->model->readed
		], GeometryDashAccountFriendRequestObjectDefinition::GLUE);
	}
}