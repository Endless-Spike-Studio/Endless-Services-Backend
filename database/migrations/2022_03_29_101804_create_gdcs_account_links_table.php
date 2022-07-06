<?php

use App\Models\GDCS\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gdcs_account_links', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class);
            $table->string('server');
            $table->string('target_name');
            $table->foreignId('target_account_id');
            $table->foreignId('target_user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_account_links');
    }
};
