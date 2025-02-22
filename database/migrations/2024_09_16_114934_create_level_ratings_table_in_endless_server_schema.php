<?php

use App\EndlessServer\Models\Level;
use App\GeometryDash\Enums\GeometryDashLevelRatingDemonDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelRatingDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelRatingStars;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.level_ratings';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Level::class)->unique();
			$table->forEnum('difficulty', GeometryDashLevelRatingDifficulties::class);
			$table->forEnum('stars', GeometryDashLevelRatingStars::class);
			$table->boolean('coin_verified');
			$table->integer('featured_score');
			$table->integer('epic_mode');
			$table->boolean('auto');
			$table->boolean('demon');
			$table->forEnum('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::class);
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};