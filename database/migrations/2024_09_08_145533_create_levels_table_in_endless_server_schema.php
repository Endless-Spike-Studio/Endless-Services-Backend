<?php

use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\Player;
use App\GeometryDash\Enums\GeometryDashLevelCoinCounts;
use App\GeometryDash\Enums\GeometryDashLevelLengths;
use App\GeometryDash\Enums\GeometryDashLevelRatingStars;
use App\GeometryDash\Enums\GeometryDashLevelUnlistedTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.levels';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Player::class);
			$table->string('name');
			$table->string('description')->nullable();
			$table->integer('version');
			$table->enum('length', [GeometryDashLevelLengths::TINY->value, GeometryDashLevelLengths::SHORT->value, GeometryDashLevelLengths::MEDIUM->value, GeometryDashLevelLengths::LONG->value, GeometryDashLevelLengths::XL->value, GeometryDashLevelLengths::PLATFORMER->value]);
			$table->integer('audio_track_id')->nullable();
			$table->integer('password')->nullable();
			$table->foreignIdFor(Level::class, 'original_level_id');
			$table->boolean('2p_mode');
			$table->integer('objects');
			$table->enum('coins', [GeometryDashLevelCoinCounts::__0->value, GeometryDashLevelCoinCounts::__1->value, GeometryDashLevelCoinCounts::__2->value, GeometryDashLevelCoinCounts::__3->value])->nullable();
			$table->enum('requested_stars', [GeometryDashLevelRatingStars::__0->value, GeometryDashLevelRatingStars::__1->value, GeometryDashLevelRatingStars::__2->value, GeometryDashLevelRatingStars::__3->value, GeometryDashLevelRatingStars::__4->value, GeometryDashLevelRatingStars::__5->value, GeometryDashLevelRatingStars::__6->value, GeometryDashLevelRatingStars::__7->value, GeometryDashLevelRatingStars::__8->value, GeometryDashLevelRatingStars::__9->value, GeometryDashLevelRatingStars::__10->value])->nullable();
			$table->enum('unlisted_type', [GeometryDashLevelUnlistedTypes::PUBLIC->value, GeometryDashLevelUnlistedTypes::FRIENDS_ONLY->value, GeometryDashLevelUnlistedTypes::SELF_ONLY->value]);
			$table->boolean('ldm_mode');
			$table->integer('editor_time')->nullable();
			$table->integer('previous_editor_time')->nullable();
			$table->string('extra')->nullable();
			$table->string('replay')->nullable();
			$table->integer('verification_time')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};