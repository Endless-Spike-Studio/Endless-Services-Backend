<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gdcs_challenges', static function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->string('name');
            $table->unsignedInteger('collect');
            $table->unsignedInteger('reward');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_challenges');
    }
};
