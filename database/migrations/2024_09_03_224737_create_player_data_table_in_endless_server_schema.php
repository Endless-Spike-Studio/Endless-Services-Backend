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
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};