<?php

use App\EndlessServer\Models\Player;
use App\GeometryDash\Enums\GeometryDashQuestCollectTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.player_quests';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Player::class);
			$table->string('name');
			$table->enum('collect_type', [GeometryDashQuestCollectTypes::ORB->value, GeometryDashQuestCollectTypes::COIN->value, GeometryDashQuestCollectTypes::STAR->value]);
			$table->integer('collect_count');
			$table->integer('reward_count');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};