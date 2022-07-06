<?php

use App\Models\GDCS\Level;
use App\Models\GDCS\User;
use App\Models\NGProxy\Song;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gdcs_levels', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->unsignedTinyInteger('game_version');
            $table->string('name');
            $table->string('desc')->nullable();
            $table->integer('downloads')->default(0);
            $table->integer('likes')->default(0);
            $table->unsignedInteger('version');
            $table->unsignedTinyInteger('length');
            $table->unsignedTinyInteger('audio_track');
            $table->foreignIdFor(Song::class);
            $table->boolean('auto');
            $table->unsignedMediumInteger('password');
            $table->foreignIdFor(Level::class, 'original_level_id');
            $table->boolean('two_player');
            $table->unsignedInteger('objects');
            $table->unsignedTinyInteger('coins');
            $table->unsignedTinyInteger('requested_stars');
            $table->boolean('unlisted');
            $table->boolean('ldm');
            $table->longText('extra_string');
            $table->string('level_info');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_levels');
    }
};
