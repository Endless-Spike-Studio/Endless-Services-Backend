<?php

use App\Models\GDCS\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gdcs_account_settings', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->index()->unique();
            $table->unsignedTinyInteger('message_state');
            $table->unsignedTinyInteger('friend_request_state');
            $table->unsignedTinyInteger('comment_history_state');
            $table->string('youtube_channel')->nullable();
            $table->string('twitter')->nullable();
            $table->string('twitch')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_account_settings');
    }
};
