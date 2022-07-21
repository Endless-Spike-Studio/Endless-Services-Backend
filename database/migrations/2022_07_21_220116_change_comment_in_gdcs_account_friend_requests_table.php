<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gdcs_account_friend_requests', static function (Blueprint $table) {
            $table->longText('comment')->change();
        });
    }

    public function down(): void
    {
        Schema::table('gdcs_account_friend_requests', static function (Blueprint $table) {
            $table->string('comment')->change();
        });
    }
};
