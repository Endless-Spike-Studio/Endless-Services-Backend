<?php

use App\EndlessServer\Models\Player;
use App\GeometryDash\Enums\GeometryDashIconTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.player_data';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Player::class)->unique();
			$table->integer('game_version');
			$table->integer('binary_version');
			$table->integer('stars');
			$table->integer('moons');
			$table->integer('demons');
			$table->integer('diamonds');
			$table->integer('icon_id');
			$table->forEnum('icon_type', GeometryDashIconTypes::class);
			$table->integer('coins');
			$table->integer('user_coins');
			$table->integer('color1');
			$table->integer('color2');
			$table->integer('color3');
			$table->integer('cube_id');
			$table->integer('ship_id');
			$table->integer('ball_id');
			$table->integer('bird_id');
			$table->integer('dart_id');
			$table->integer('robot_id');
			$table->integer('glow_id');
			$table->integer('spider_id');
			$table->integer('explosion_id');
			$table->integer('swing_id');
			$table->integer('jetpack_id');
			$table->integer('special');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};