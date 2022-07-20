<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gdcs_levels', static function (Blueprint $table) {
            $table->longText('level_info')->change();
        });
    }

    public function down(): void
    {
        Schema::table('gdcs_levels', static function (Blueprint $table) {
            $table->string('level_info')->change();
        });
    }
};
