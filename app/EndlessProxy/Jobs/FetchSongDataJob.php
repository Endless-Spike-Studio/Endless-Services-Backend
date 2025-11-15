<?php

namespace App\EndlessProxy\Jobs;

use App\EndlessProxy\Models\NewgroundsSong;
use App\EndlessProxy\Services\NewgroundsAudioStorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchSongDataJob implements ShouldQueue, ShouldBeUniqueUntilProcessing
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public function __construct(
		protected readonly NewgroundsSong $song
	)
	{

	}

	public function handle(): void
	{
		$service = app(NewgroundsAudioStorageService::class);
		$service->song = $this->song;
		$service->fetch();
	}

	public function uniqueId(): string
	{
		return $this->song->id;
	}
}