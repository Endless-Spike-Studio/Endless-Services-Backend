<?php

use App\EndlessServer\Models\Level;
use App\GeometryDash\Enums\GeometryDashLevelRatingDemonDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelRatingDifficulties;
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
			$table->enum('difficulty', [GeometryDashLevelRatingDifficulties::EASY->value, GeometryDashLevelRatingDifficulties::NORMAL->value, GeometryDashLevelRatingDifficulties::HARD->value, GeometryDashLevelRatingDifficulties::HARDER->value, GeometryDashLevelRatingDifficulties::INSANE->value, GeometryDashLevelRatingDifficulties::AUTO_AND_DEMON->value])->nullable();
			$table->enum('stars', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->nullable();
			$table->boolean('coin_verified');
			$table->unsignedBigInteger('featured_score');
			$table->unsignedBigInteger('epic_mode');
			$table->boolean('auto');
			$table->boolean('demon');
			$table->enum('demon_difficulty', [GeometryDashLevelRatingDemonDifficulties::EASY->value, GeometryDashLevelRatingDemonDifficulties::MEDIUM->value, GeometryDashLevelRatingDemonDifficulties::HARD->value, GeometryDashLevelRatingDemonDifficulties::INSANE->value, GeometryDashLevelRatingDemonDifficulties::EXTREME->value]);
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};