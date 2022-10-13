<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('gdcs_level_transfer_records', function (Blueprint $table) {
            $table->id();
            $table->string('server');
            $table->foreignId('account_id');
            $table->foreignId('original_level_id');
            $table->foreignId('level_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gdcs_level_transfer_records');
    }
};
