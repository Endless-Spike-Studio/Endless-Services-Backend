<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_proxy.newgrounds_songs';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignId('song_id')->unique();
			$table->string('name');
			$table->foreignId('artist_id')->nullable();
			$table->string('artist_name');
			$table->decimal('size')->nullable();
			$table->string('original_download_url');
			$table->boolean('disabled');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};