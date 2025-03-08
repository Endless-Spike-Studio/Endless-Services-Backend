<?php

use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\MapPack;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.map_pack_levels';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(MapPack::class);
			$table->foreignIdFor(Level::class);
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};