<?php

use App\EndlessServer\Models\Level;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.level_ratings';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Level::class);
			$table->unsignedTinyInteger('difficulty');
			$table->unsignedTinyInteger('stars');
			$table->boolean('coin_verified');
			$table->unsignedInteger('featured_score');
			$table->unsignedTinyInteger('epic_mode');
			$table->boolean('auto');
			$table->boolean('demon');
			$table->unsignedTinyInteger('demon_difficulty');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};