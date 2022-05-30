<?php

use App\Models\GDCS\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gdcs_user_daily_chest', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->index()->unique();
            $table->timestamp('small_time')->nullable();
            $table->unsignedInteger('small_count')->default(0);
            $table->timestamp('big_time')->nullable();
            $table->unsignedInteger('big_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_user_daily_chest');
    }
};
