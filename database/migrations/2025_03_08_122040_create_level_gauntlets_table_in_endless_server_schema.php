<?php

use App\EndlessServer\Models\Level;
use App\GeometryDash\Enums\GeometryDashLevelGauntlets;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.level_gauntlets';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->forEnum('gauntlet_id', GeometryDashLevelGauntlets::class)->unique();
			$table->foreignIdFor(Level::class, 'level1_id');
			$table->foreignIdFor(Level::class, 'level2_id');
			$table->foreignIdFor(Level::class, 'level3_id');
			$table->foreignIdFor(Level::class, 'level4_id');
			$table->foreignIdFor(Level::class, 'level5_id');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};