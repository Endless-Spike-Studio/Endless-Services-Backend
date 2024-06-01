<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
	public function up(): void
	{
		DB::unprepared('CREATE SCHEMA endless_proxy');
	}

	public function down(): void
	{
		DB::unprepared('DROP SCHEMA IF EXISTS endless_proxy');
	}
};