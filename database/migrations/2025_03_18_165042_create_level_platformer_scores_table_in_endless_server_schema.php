<?php

use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Level;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.level_platformer_scores';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Account::class);
			$table->foreignIdFor(Level::class);
			$table->integer('time');
			$table->integer('points');
			$table->integer('attempts');
			$table->integer('clicks');
			$table->integer('attempt_seconds');
			$table->integer('coins');
			$table->foreignId('special_id')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};