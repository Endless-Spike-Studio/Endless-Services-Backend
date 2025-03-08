<?php

use App\GeometryDash\Enums\GeometryDashMapPackDifficulties;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.map_packs';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->unsignedInteger('stars');
			$table->unsignedInteger('coins');
			$table->forEnum('difficulty', GeometryDashMapPackDifficulties::class)->nullable();
			$table->string('text_color')->nullable();
			$table->string('bar_color')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};