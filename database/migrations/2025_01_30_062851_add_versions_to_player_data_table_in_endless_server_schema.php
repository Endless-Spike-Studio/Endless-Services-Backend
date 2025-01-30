<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.player_data';

	public function up(): void
	{
		Schema::table($this->name, function (Blueprint $table) {
			$table->unsignedBigInteger('game_version')->after('player_id');
			$table->unsignedBigInteger('binary_version')->after('game_version');
		});
	}

	public function down(): void
	{
		Schema::table($this->name, function (Blueprint $table) {
			$table->dropColumn('game_version');
			$table->dropColumn('binary_version');
		});
	}
};