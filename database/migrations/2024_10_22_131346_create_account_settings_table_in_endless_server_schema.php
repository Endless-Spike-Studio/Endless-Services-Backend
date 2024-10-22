<?php

use App\EndlessServer\Models\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	protected string $name = 'endless_server.account_settings';

	public function up(): void
	{
		Schema::create($this->name, function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Account::class)->unique();
			$table->unsignedTinyInteger('message_state');
			$table->unsignedTinyInteger('friend_request_state');
			$table->unsignedTinyInteger('comment_history_state');
			$table->string('youtube');
			$table->string('twitter');
			$table->string('twitch');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists($this->name);
	}
};