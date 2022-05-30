<?php

use App\Models\GDCS\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gdcs_banned_users', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->boolean('login_ban')->default(false);
            $table->boolean('comment_ban')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_banned_users');
    }
};
