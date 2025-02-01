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
			$table->enum('message_state', [GeometryDashAccountSettingMessageStates::ALL->value, GeometryDashAccountSettingMessageStates::FRIENDS_ONLY->value, GeometryDashAccountSettingMessageStates::NONE->value]);
			$table->enum('friend_request_state', [GeometryDashAccountSettingFriendRequestStates::ALL->value, GeometryDashAccountSettingFriendRequestStates::NONE->value]);
			$table->enum('comment_history_state', [GeometryDashAccountSettingCommentHistoryStates::ALL->value, GeometryDashAccountSettingCommentHistoryStates::FRIENDS_ONLY->value, GeometryDashAccountSettingCommentHistoryStates::SELF_ONLY->value]);
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