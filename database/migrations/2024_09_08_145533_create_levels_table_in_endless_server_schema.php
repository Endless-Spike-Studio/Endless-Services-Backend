<?php

use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\Player;
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
			$table->string('description');
			$table->unsignedInteger('version');
			$table->unsignedTinyInteger('length');
			$table->unsignedTinyInteger('audio_track_id');
			$table->string('password');
			$table->foreignIdFor(Level::class, 'original_level_id');
			$table->boolean('2p_mode');
			$table->unsignedInteger('objects');
			$table->unsignedTinyInteger('coins');
			$table->unsignedTinyInteger('requested_stars');
			$table->unsignedTinyInteger('unlisted_type');
			$table->boolean('ldm_mode');
			$table->unsignedInteger('wt')->nullable();
			$table->unsignedInteger('wt2')->nullable();
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