<?php

use App\Models\GDCS\User;
use App\Models\NGProxy\Song;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('gdproxy_level_song_replaces', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignId('level_id');
            $table->foreignIdFor(Song::class);
            $table->float('offset')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gdproxy_level_song_replaces');
    }
};
