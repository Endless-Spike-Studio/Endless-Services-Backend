<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gdcs_level_packs', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('levels');
            $table->unsignedTinyInteger('stars');
            $table->unsignedTinyInteger('coins')->default(0);
            $table->unsignedTinyInteger('difficulty');
            $table->string('text_color')->default('255,255,255');
            $table->string('bar_color')->default('255,255,255');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_level_packs');
    }
};
