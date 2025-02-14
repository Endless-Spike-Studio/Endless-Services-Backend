<?php

namespace App\EndlessServer\Services;

use App\EndlessProxy\Services\GeometryDashProxyService;
use App\EndlessServer\Models\Level;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class GameLevelDataStorageService
{
	public Level $level {
		set {
			$this->level = $value;

			$this->path = $this->format;
			$this->path = str_replace('{id}', $this->level->id, $this->path);
		}
	}

	protected string $disk;
	protected string $format;

	protected Filesystem $storage;

	protected string $path;

	public function __construct(
		protected readonly GeometryDashProxyService $proxy
	)
	{
		$this->disk = config('services.endless.server.level_data.storage.disk');
		$this->format = config('services.endless.server.level_data.storage.format');

		$this->storage = Storage::disk($this->disk);
	}

	public function fetch(): ?string
	{
		return $this->storage->get($this->path);
	}

	public function store(string $content): void
	{
		$this->storage->put($this->path, $content);
	}
}