<?php

use App\Models\GDCS\Level;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gdcs_level_gauntlets', static function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('gauntlet_id')->unique();
            $table->foreignIdFor(Level::class, 'level1_id');
            $table->foreignIdFor(Level::class, 'level2_id');
            $table->foreignIdFor(Level::class, 'level3_id');
            $table->foreignIdFor(Level::class, 'level4_id');
            $table->foreignIdFor(Level::class, 'level5_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_level_gauntlets');
    }
};
