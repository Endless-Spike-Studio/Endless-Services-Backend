<?php

use App\Models\GDCS\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gdcs_user_scores', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->index()->unique();
            $table->unsignedInteger('stars');
            $table->unsignedInteger('demons');
            $table->unsignedSmallInteger('diamonds');
            $table->unsignedSmallInteger('icon');
            $table->unsignedSmallInteger('icon_type');
            $table->unsignedInteger('coins');
            $table->unsignedInteger('user_coins');
            $table->unsignedSmallInteger('color1');
            $table->unsignedSmallInteger('color2')->default(3);
            $table->unsignedSmallInteger('special');
            $table->unsignedSmallInteger('acc_icon');
            $table->unsignedSmallInteger('acc_ship');
            $table->unsignedSmallInteger('acc_ball');
            $table->unsignedSmallInteger('acc_bird');
            $table->unsignedSmallInteger('acc_dart');
            $table->unsignedSmallInteger('acc_robot');
            $table->unsignedSmallInteger('acc_glow');
            $table->unsignedSmallInteger('acc_spider');
            $table->unsignedSmallInteger('acc_explosion');
            $table->unsignedInteger('creator_points')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_user_scores');
    }
};
