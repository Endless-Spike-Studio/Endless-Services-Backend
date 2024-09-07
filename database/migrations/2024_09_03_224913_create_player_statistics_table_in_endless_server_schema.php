<?php

use App\EndlessServer\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.player_statistics';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Player::class)->unique();
			$table->integer('completed_dailies_count');
			$table->integer('completed_weeklies_count');
			$table->integer('completed_classic_auto_count');
			$table->integer('completed_classic_easy_count');
			$table->integer('completed_classic_normal_count');
			$table->integer('completed_classic_hard_count');
			$table->integer('completed_classic_harder_count');
			$table->integer('completed_classic_insane_count');
			$table->integer('completed_platformer_auto_count');
			$table->integer('completed_platformer_easy_count');
			$table->integer('completed_platformer_normal_count');
			$table->integer('completed_platformer_hard_count');
			$table->integer('completed_platformer_harder_count');
			$table->integer('completed_platformer_insane_count');
			$table->integer('completed_classic_easy_demons_count');
			$table->integer('completed_classic_medium_demons_count');
			$table->integer('completed_classic_hard_demons_count');
			$table->integer('completed_classic_insane_demons_count');
			$table->integer('completed_classic_extreme_demons_count');
			$table->integer('completed_platformer_easy_demons_count');
			$table->integer('completed_platformer_medium_demons_count');
			$table->integer('completed_platformer_hard_demons_count');
			$table->integer('completed_platformer_insane_demons_count');
			$table->integer('completed_platformer_extreme_demons_count');
			$table->integer('completed_gauntlet_levels_count');
			$table->integer('completed_gauntlet_demon_levels_count');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};