<?php

use App\Models\GDCS\Level;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gdcs_daily_levels', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Level::class);
            $table->date('apply_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_daily_levels');
    }
};
