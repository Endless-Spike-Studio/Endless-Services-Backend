<?php

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gdcs_level_scores', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Level::class);
            $table->foreignIdFor(Account::class);
            $table->unsignedInteger('attempts')->default(0);
            $table->unsignedTinyInteger('percent')->default(0);
            $table->unsignedTinyInteger('coins')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('level_scores');
    }
};
