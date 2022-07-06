<?php

use App\Models\GDCS\Level;
use App\Models\GDCS\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gdcs_level_star_suggestions', static function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip');
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Level::class);
            $table->unsignedTinyInteger('stars');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_level_star_suggestions');
    }
};
