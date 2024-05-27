<?php

namespace App\NewgroundsProxy\Entities;

use App\NewgroundsProxy\Controllers\SongApiController;
use App\NewgroundsProxy\Services\SongStorageService;
use App\Services\Game\ObjectService;
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
			'download_url' => URL::action([SongApiController::class, 'download'], $this->id),
			'downloadable' => app(SongStorageService::class)->allValid(['id' => $this->song_id])
		];
	}

	public function toObject(): string
	{
		return ObjectService::merge([
			1 => $this->song_id,
			2 => $this->name,
			3 => $this->artist_id,
			4 => $this->artist_name,
			5 => $this->size,
			10 => $this->download_url,
		], '~|~');
	}
}