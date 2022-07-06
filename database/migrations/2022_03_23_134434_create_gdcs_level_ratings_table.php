<?php

use App\Models\GDCS\Level;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gdcs_level_ratings', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Level::class);
            $table->unsignedTinyInteger('stars');
            $table->unsignedTinyInteger('difficulty');
            $table->unsignedInteger('featured_score')->default(0);
            $table->boolean('epic')->default(false);
            $table->boolean('coin_verified')->default(false);
            $table->unsignedTinyInteger('demon_difficulty')->default(0);
            $table->boolean('auto')->default(false);
            $table->boolean('demon')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_level_ratings');
    }
};
