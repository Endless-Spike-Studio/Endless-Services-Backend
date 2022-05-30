<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ngproxy_songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('song_id')->unique();
            $table->string('name');
            $table->foreignId('artist_id');
            $table->string('artist_name');
            $table->decimal('size');
            $table->boolean('disabled');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ngproxy_songs');
    }
};
