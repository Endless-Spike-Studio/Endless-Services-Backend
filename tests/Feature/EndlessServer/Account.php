<?php

use App\EndlessServer\Controllers\GameAccountController;
use App\EndlessServer\Models\Account;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use function Pest\Laravel\post;

test('register', function () {
	$faker = fake();
	$faker = $faker->unique();

	$request = post(URL::action([GameAccountController::class, 'register']), [
		'userName' => $faker->userName,
		'password' => $faker->password,
		'email' => $faker->safeEmail,
		'secret' => GeometryDashSecrets::ACCOUNT->value
	]);

	$request->assertOk();
	$request->assertContent(
		(string)GeometryDashResponses::ACCOUNT_REGISTER_SUCCESS->value
	);
});

test('login', function () {
	$faker = fake();
	$faker = $faker->unique();

	$password = $faker->password;

	/** @var Account $account */
	$account = Account::factory()
		->createOne([
			'password' => $password
		]);

	$account->markEmailAsVerified();

	$request = post(URL::action([GameAccountController::class, 'login']), [
		'userName' => $account->name,
		'password' => $password,
		'udid' => Str::uuid()
			->toString(),
		'secret' => GeometryDashSecrets::ACCOUNT->value
	]);

	$request->assertOk();

	$request->assertContent(
		implode(',', [$account->id, $account->player->id])
	);
});