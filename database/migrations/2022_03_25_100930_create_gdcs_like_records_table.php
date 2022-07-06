<?php

use App\Models\GDCS\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gdcs_like_records', static function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->ipAddress('ip');
            $table->foreignId('item_id');
            $table->foreignIdFor(User::class)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_like_records');
    }
};
