<?php

namespace App\NewgroundsProxy\Entities;

use App\GeometryDash\Enums\Objects\Song as SongObject;
use App\GeometryDash\Services\ObjectService;
use App\NewgroundsProxy\Controllers\SongApiController;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Song extends Model
{
	protected $table = 'ngproxy_songs';

	protected $fillable = ['song_id', 'name', 'artist_id', 'artist_name', 'size', 'disabled', 'original_download_url'];

	public function toArray(): array
	{
		return [
			...parent::toArray(),
			'download_url' => $this->download_url
		];
	}

	public function downloadUrl(): Attribute
	{
		return new Attribute(
			get: fn() => URL::action([SongApiController::class, 'download'], $this->id)
		);
	}

	public function toObject(): string
	{
		return ObjectService::merge([
			SongObject::ID->value => $this->song_id,
			SongObject::NAME->value => $this->name,
			SongObject::ARTIST_ID->value => $this->artist_id,
			SongObject::ARTIST_NAME->value => $this->artist_name,
			SongObject::SIZE->value => $this->size,
			SongObject::DOWNLOAD_URL->value => $this->download_url
		], SongObject::SYMBOL);
	}
}