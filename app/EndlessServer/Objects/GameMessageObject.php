<?php

namespace App\EndlessServer\Objects;

use App\EndlessServer\Models\AccountMessage;
use App\GeometryDash\Enums\GeometryDashXorKeys;
use App\GeometryDash\Enums\Objects\GeometryDashMessageObjectDefinition;
use App\GeometryDash\Objects\GameObject;
use App\GeometryDash\Services\GeometryDashAlgorithmService;
use Base64Url\Base64Url;

readonly class GameMessageObject extends GameObject
{
	public function __construct(
		protected AccountMessage $model,
		protected bool           $getSent
	)
	{
		parent::__construct(GeometryDashMessageObjectDefinition::class, GeometryDashMessageObjectDefinition::GLUE);
	}

	protected function properties(): array
	{
		$targetAccount = $this->targetAccount();

		return [
			GeometryDashMessageObjectDefinition::ID->value => function () {
				return $this->model->id;
			},
			GeometryDashMessageObjectDefinition::ACCOUNT_ID->value => function () use ($targetAccount) {
				return $targetAccount->id;
			},
			GeometryDashMessageObjectDefinition::PLAYER_ID->value => function () use ($targetAccount) {
				return $targetAccount->player->id;
			},
			GeometryDashMessageObjectDefinition::SUBJECT->value => function () {
				return Base64Url::encode($this->model->subject, true);
			},
			GeometryDashMessageObjectDefinition::BODY->value => function () {
				return Base64Url::encode(app(GeometryDashAlgorithmService::class)->xor($this->model->body, GeometryDashXorKeys::MESSAGE->value), true);
			},
			GeometryDashMessageObjectDefinition::PLAYER_NAME->value => function () use ($targetAccount) {
				return $targetAccount->player->name;
			},
			GeometryDashMessageObjectDefinition::AGE->value => function () {
				return $this->model->created_at->diffForHumans(syntax: true);
			},
			GeometryDashMessageObjectDefinition::IS_READ->value => function () {
				return !$this->model->new;
			},
			GeometryDashMessageObjectDefinition::IS_SENDER->value => function () {
				return $this->getSent;
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