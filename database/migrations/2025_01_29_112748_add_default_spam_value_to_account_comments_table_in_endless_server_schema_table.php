<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.account_comments';

	public function up(): void
	{
		Schema::table($this->name, function (Blueprint $table) {
			$table->boolean('spam')->default(false)->change();
		});
	}

	public function down(): void
	{
		Schema::table($this->name, function (Blueprint $table) {
			$table->boolean('spam')->change();
		});
	}
};
