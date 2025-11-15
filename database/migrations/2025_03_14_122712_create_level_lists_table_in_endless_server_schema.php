<?php

use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\LevelList;
use App\GeometryDash\Enums\GeometryDashLevelListDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelListUnlistedTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.level_lists';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Account::class);
			$table->string('name');
			$table->string('description')->nullable();
			$table->forEnum('difficulty', GeometryDashLevelListDifficulties::class);
			$table->integer('version');
			$table->foreignIdFor(LevelList::class, 'original_level_list_id')->nullable();
			$table->forEnum('unlisted_type', GeometryDashLevelListUnlistedTypes::class);
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};