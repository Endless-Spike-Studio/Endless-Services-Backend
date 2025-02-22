<?php

namespace App\EndlessServer\Responses;

use App\EndlessServer\Models\AccountMessage;
use App\GeometryDash\Enums\GeometryDashXorKeys;
use App\GeometryDash\Enums\Objects\GeometryDashMessageObjectDefinition;
use App\GeometryDash\Services\GeometryDashAlgorithmService;
use App\GeometryDash\Services\GeometryDashObjectService;
use Base64Url\Base64Url;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Carbon;

readonly class GameMessageObjectResponse implements Responsable
{
	public function __construct(
		protected AccountMessage $model,
		protected bool           $getSent
	)
	{
		Carbon::setLocale('en');
	}

	public function toResponse($request)
	{
		$targetAccount = $this->model->account;

		if ($this->getSent) {
			$targetAccount = $this->model->targetAccount;
		}

		return app(GeometryDashObjectService::class)->merge([
			GeometryDashMessageObjectDefinition::ID->value => $this->model->id,
			GeometryDashMessageObjectDefinition::ACCOUNT_ID->value => $targetAccount->id,
			GeometryDashMessageObjectDefinition::PLAYER_ID->value => $targetAccount->player->id,
			GeometryDashMessageObjectDefinition::SUBJECT->value => Base64Url::encode($this->model->subject, true),
			GeometryDashMessageObjectDefinition::BODY->value => Base64Url::encode(app(GeometryDashAlgorithmService::class)->xor($this->model->body, GeometryDashXorKeys::MESSAGE->value), true),
			GeometryDashMessageObjectDefinition::PLAYER_NAME->value => $targetAccount->player->name,
			GeometryDashMessageObjectDefinition::AGE->value => $this->model->created_at->diffForHumans(syntax: true),
			GeometryDashMessageObjectDefinition::IS_READ->value => $this->model->readed,
			GeometryDashMessageObjectDefinition::IS_SENDER->value => $this->getSent
		], GeometryDashMessageObjectDefinition::GLUE);
	}
}