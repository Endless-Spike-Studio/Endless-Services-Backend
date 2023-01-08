<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('gdcs_contests', function (Blueprint $table) {
            $table->timestamp('deadline_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('gdcs_contests', function (Blueprint $table) {
            $table->dropColumn('deadline_at');
        });
    }
};
