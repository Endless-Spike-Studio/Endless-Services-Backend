<?php

use App\EndlessServer\Models\Player;
use App\GeometryDash\Enums\GeometryDashRewardTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.player_chest_records';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Player::class);
			$table->enum('type', [GeometryDashRewardTypes::SMALL->value, GeometryDashRewardTypes::BIG->value]);
			$table->unsignedBigInteger('orbs');
			$table->unsignedBigInteger('diamonds');
			$table->unsignedBigInteger('shards');
			$table->unsignedBigInteger('keys');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};
