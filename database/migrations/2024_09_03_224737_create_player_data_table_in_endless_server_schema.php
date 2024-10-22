<?php

use App\EndlessServer\Models\Player;
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
			$table->unsignedSmallInteger('stars');
			$table->unsignedSmallInteger('moons');
			$table->unsignedSmallInteger('demons');
			$table->unsignedSmallInteger('diamonds');
			$table->unsignedSmallInteger('icon_id');
			$table->unsignedSmallInteger('icon_type');
			$table->unsignedSmallInteger('coins');
			$table->unsignedSmallInteger('user_coins');
			$table->unsignedSmallInteger('color1');
			$table->unsignedSmallInteger('color2');
			$table->unsignedSmallInteger('color3');
			$table->unsignedSmallInteger('cube_id');
			$table->unsignedSmallInteger('ship_id');
			$table->unsignedSmallInteger('ball_id');
			$table->unsignedSmallInteger('bird_id');
			$table->unsignedSmallInteger('dart_id');
			$table->unsignedSmallInteger('robot_id');
			$table->unsignedSmallInteger('glow_id');
			$table->unsignedSmallInteger('spider_id');
			$table->unsignedSmallInteger('explosion_id');
			$table->unsignedSmallInteger('swing_id');
			$table->unsignedSmallInteger('jetpack_id');
			$table->unsignedTinyInteger('special');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};