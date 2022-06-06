<?php

use App\Models\GDCS\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('gdcs_account_failed_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class);
            $table->string('content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gdcs_account_failed_logs');
    }
};
