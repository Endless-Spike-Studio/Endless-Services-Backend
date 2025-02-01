<?php

use App\EndlessProxy\Models\NewgroundsSong;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\Player;
use App\GeometryDash\Enums\GeometryDashLevelLengths;
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
			$table->unsignedBigInteger('version');
			$table->enum('length', [GeometryDashLevelLengths::TINY->value, GeometryDashLevelLengths::SHORT->value, GeometryDashLevelLengths::MEDIUM->value, GeometryDashLevelLengths::LONG->value, GeometryDashLevelLengths::XL->value, GeometryDashLevelLengths::PLATFORMER->value]);
			$table->unsignedBigInteger('audio_track_id')->nullable();
			$table->foreignIdFor(NewgroundsSong::class)->nullable();
			$table->string('password')->nullable();
			$table->foreignIdFor(Level::class, 'original_level_id');
			$table->boolean('2p_mode');
			$table->unsignedBigInteger('objects');
			$table->enum('coins', [1, 2, 3])->nullable();
			$table->enum('requested_stars', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->nullable();
			$table->unsignedBigInteger('unlisted_type');
			$table->boolean('ldm_mode');
			$table->unsignedBigInteger('wt')->nullable();
			$table->unsignedBigInteger('wt2')->nullable();
			$table->string('extra')->nullable();
			$table->string('replay')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};