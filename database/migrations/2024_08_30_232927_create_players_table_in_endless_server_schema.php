<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.players';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('uuid')->unique();
			$table->string('udid');
			$table->integer('stars')->nullable();
			$table->integer('moons')->nullable();
			$table->integer('demons')->nullable();
			$table->integer('diamonds')->nullable();
			$table->integer('icon_id')->nullable();
			$table->integer('icon_type')->nullable();
			$table->integer('coins')->nullable();
			$table->integer('user_coins')->nullable();
			$table->integer('cube_id')->nullable();
			$table->integer('ship_id')->nullable();
			$table->integer('ball_id')->nullable();
			$table->integer('bird_id')->nullable();
			$table->integer('dart_id')->nullable();
			$table->integer('robot_id')->nullable();
			$table->integer('glow_id')->nullable();
			$table->integer('spider_id')->nullable();
			$table->integer('explosion_id')->nullable();
			$table->integer('swing_id')->nullable();
			$table->integer('jetpack_id')->nullable();
			$table->integer('completed_dailies_count')->nullable();
			$table->integer('completed_weeklies_count')->nullable();
			$table->integer('completed_classic_auto_count')->nullable();
			$table->integer('completed_classic_easy_count')->nullable();
			$table->integer('completed_classic_normal_count')->nullable();
			$table->integer('completed_classic_hard_count')->nullable();
			$table->integer('completed_classic_harder_count')->nullable();
			$table->integer('completed_classic_insane_count')->nullable();
			$table->integer('completed_platformer_auto_count')->nullable();
			$table->integer('completed_platformer_easy_count')->nullable();
			$table->integer('completed_platformer_normal_count')->nullable();
			$table->integer('completed_platformer_hard_count')->nullable();
			$table->integer('completed_platformer_harder_count')->nullable();
			$table->integer('completed_platformer_insane_count')->nullable();
			$table->integer('completed_classic_easy_demons_count')->nullable();
			$table->integer('completed_classic_medium_demons_count')->nullable();
			$table->integer('completed_classic_hard_demons_count')->nullable();
			$table->integer('completed_classic_insane_demons_count')->nullable();
			$table->integer('completed_classic_extreme_demons_count')->nullable();
			$table->integer('completed_platformer_easy_demons_count')->nullable();
			$table->integer('completed_platformer_medium_demons_count')->nullable();
			$table->integer('completed_platformer_hard_demons_count')->nullable();
			$table->integer('completed_platformer_insane_demons_count')->nullable();
			$table->integer('completed_platformer_extreme_demons_count')->nullable();
			$table->integer('completed_gauntlet_levels_count')->nullable();
			$table->integer('completed_gauntlet_demon_levels_count')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};