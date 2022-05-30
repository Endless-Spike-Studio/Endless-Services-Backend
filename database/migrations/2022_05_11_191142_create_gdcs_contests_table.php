<?php

use App\Models\GDCS\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gdcs_contests', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class);
            $table->string('name');
            $table->string('desc');
            $table->json('rules');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_contests');
    }
};
