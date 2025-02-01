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
			$table->unsignedBigInteger('game_version');
			$table->unsignedBigInteger('binary_version');
			$table->unsignedBigInteger('stars');
			$table->unsignedBigInteger('moons');
			$table->unsignedBigInteger('demons');
			$table->unsignedBigInteger('diamonds');
			$table->unsignedBigInteger('icon_id');
			$table->enum('icon_type', [GeometryDashIconTypes::CUBE->value, GeometryDashIconTypes::SHIP->value, GeometryDashIconTypes::BALL->value, GeometryDashIconTypes::UFO->value, GeometryDashIconTypes::WAVE->value, GeometryDashIconTypes::ROBOT->value, GeometryDashIconTypes::SPIDER->value, GeometryDashIconTypes::SWING->value, GeometryDashIconTypes::JETPACK->value]);
			$table->unsignedBigInteger('coins');
			$table->unsignedBigInteger('user_coins');
			$table->unsignedBigInteger('color1');
			$table->unsignedBigInteger('color2');
			$table->unsignedBigInteger('color3');
			$table->unsignedBigInteger('cube_id');
			$table->unsignedBigInteger('ship_id');
			$table->unsignedBigInteger('ball_id');
			$table->unsignedBigInteger('bird_id');
			$table->unsignedBigInteger('dart_id');
			$table->unsignedBigInteger('robot_id');
			$table->unsignedBigInteger('glow_id');
			$table->unsignedBigInteger('spider_id');
			$table->unsignedBigInteger('explosion_id');
			$table->unsignedBigInteger('swing_id');
			$table->unsignedBigInteger('jetpack_id');
			$table->unsignedBigInteger('special');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};