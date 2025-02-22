<?php

namespace App\EndlessServer\Responses;

use App\EndlessServer\Models\AccountComment;
use App\GeometryDash\Enums\Objects\GeometryDashCommentObjectDefinitions;
use App\GeometryDash\Services\GeometryDashObjectService;
use Base64Url\Base64Url;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Carbon;

readonly class GameAccountCommentObjectResponse implements Responsable
{
	public function __construct(
		protected AccountComment $model
	)
	{
		Carbon::setLocale('en');
	}

	public function toResponse($request)
	{
		return app(GeometryDashObjectService::class)->merge([
			GeometryDashCommentObjectDefinitions::LEVEL_ID->value => 0, // TODO
			GeometryDashCommentObjectDefinitions::CONTENT->value => Base64Url::encode($this->model->content, true),
			GeometryDashCommentObjectDefinitions::PLAYER_ID->value => $this->model->account->player->id,
			GeometryDashCommentObjectDefinitions::LIKES->value => 0, // TODO,
			GeometryDashCommentObjectDefinitions::ID->value => $this->model->id,
			GeometryDashCommentObjectDefinitions::IS_SPAM->value => $this->model->spam,
			GeometryDashCommentObjectDefinitions::AGE->value => $this->model->created_at->diffForHumans(syntax: true),
			GeometryDashCommentObjectDefinitions::PERCENT->value => 0, // TODO
			GeometryDashCommentObjectDefinitions::MOD_BADGE->value => $this->model->account->mod_level,
			GeometryDashCommentObjectDefinitions::COLOR->value => $this->model->account->comment_color
		], GeometryDashCommentObjectDefinitions::GLUE);
	}
}