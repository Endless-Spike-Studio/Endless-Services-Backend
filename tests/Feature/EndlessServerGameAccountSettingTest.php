<?php

use App\EndlessServer\Controllers\GameAccountSettingController;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountSetting;
use App\EndlessServer\Services\GameAccountService;
use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryState;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestState;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageState;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Services\GeometryDashAlgorithmService;
use Illuminate\Support\Facades\URL;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

test('update', function () {
	$faker = fake();
	$faker = $faker->unique();

	$password = $faker->password;

	/** @var Account $account */
	$account = Account::factory()
		->createOne([
			'password' => $password
		]);

	app(GameAccountService::class)->storageGjp2($account, $password);

	$account->markEmailAsVerified();

	$youtube = $faker->word();
	$twitch = $faker->word();
	$twitter = $faker->word();

	$request = post(URL::action([GameAccountSettingController::class, 'update']), [
		'accountID' => $account->id,
		'gjp2' => app(GeometryDashAlgorithmService::class)->generateGjp2($password),
		'mS' => GeometryDashAccountSettingMessageState::FRIENDS_ONLY->value,
		'frS' => GeometryDashAccountSettingFriendRequestState::NONE->value,
		'cS' => GeometryDashAccountSettingCommentHistoryState::FRIENDS_ONLY->value,
		'yt' => $youtube,
		'twitter' => $twitter,
		'twitch' => $twitch,
		'secret' => 'Wmfv3899gc9'
	]);

	$request->assertOk();
	$request->assertContent(
		(string)GeometryDashResponses::ACCOUNT_SETTING_UPDATE_SUCCESS->value
	);

	assertDatabaseHas(AccountSetting::class, [
		'account_id' => $account->id,
		'message_state' => GeometryDashAccountSettingMessageState::FRIENDS_ONLY->value,
		'friend_request_state' => GeometryDashAccountSettingFriendRequestState::NONE->value,
		'comment_history_state' => GeometryDashAccountSettingCommentHistoryState::FRIENDS_ONLY->value,
		'youtube' => $youtube,
		'twitter' => $twitter,
		'twitch' => $twitch
	]);
});