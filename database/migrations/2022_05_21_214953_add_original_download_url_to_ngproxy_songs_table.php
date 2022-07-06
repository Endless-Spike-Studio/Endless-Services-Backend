<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ngproxy_songs', static function (Blueprint $table) {
            $table->string('original_download_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ngproxy_songs', static function (Blueprint $table) {
            $table->dropColumn('original_download_url');
        });
    }
};
