<?php

use App\Models\GDCS\Account;
use App\Models\GDCS\Contest;
use App\Models\GDCS\Level;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('gdcs_contest_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Contest::class);
            $table->foreignIdFor(Account::class);
            $table->foreignIdFor(Level::class);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gdcs_contest_participants');
    }
};
