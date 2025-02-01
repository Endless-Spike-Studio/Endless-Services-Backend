<?php

use App\GeometryDash\Enums\GeometryDashModLevels;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.roles';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->enum('mod_level', [GeometryDashModLevels::PLAYER->value, GeometryDashModLevels::MOD->value, GeometryDashModLevels::ELDER_MOD->value]);
			$table->string('comment_color')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};
