<?php

use App\Models\GDCS\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gdcs_level_transfer_records', static function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->foreignIdFor(Account::class);
            $table->foreignId('original_level_id');
            $table->foreignId('level_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_level_transfer_records');
    }
};
