<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gdcs_account_failed_logs', static function (Blueprint $table) {
            $table->ipAddress('ip')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('gdcs_account_failed_logs', static function (Blueprint $table) {
            $table->dropColumn('ip');
        });
    }
};
