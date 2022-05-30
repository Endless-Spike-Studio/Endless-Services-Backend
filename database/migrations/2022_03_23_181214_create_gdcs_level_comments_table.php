<?php

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gdcs_level_comments', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->index();
            $table->foreignIdFor(Level::class)->index();
            $table->string('comment');
            $table->unsignedTinyInteger('percent')->default(0);
            $table->unsignedInteger('likes')->default(0);
            $table->boolean('spam')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gdcs_level_comments');
    }
};
