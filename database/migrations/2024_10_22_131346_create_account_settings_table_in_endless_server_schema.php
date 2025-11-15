<?php

use App\EndlessServer\Models\Account;
use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageStates;
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
			$table->forEnum('message_state', GeometryDashAccountSettingMessageStates::class);
			$table->forEnum('friend_request_state', GeometryDashAccountSettingFriendRequestStates::class);
			$table->forEnum('comment_history_state', GeometryDashAccountSettingCommentHistoryStates::class);
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