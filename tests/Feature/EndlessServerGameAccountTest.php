<?php

use App\EndlessServer\Controllers\GameAccountController;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountGjp2Binding;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\GeometryDashSecrets;
use App\GeometryDash\Services\GeometryDashAlgorithmService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

test('register', function () {
	$faker = fake();
	$faker = $faker->unique();

	$name = $faker->userName;

	$request = post(URL::action([GameAccountController::class, 'register']), [
		'userName' => $name,
		'password' => $faker->password,
		'email' => $faker->safeEmail,
		'secret' => GeometryDashSecrets::ACCOUNT->value
	]);

	$request->assertOk();
	$request->assertContent(
		(string)GeometryDashResponses::ACCOUNT_REGISTER_SUCCESS->value
	);

	assertDatabaseHas(Account::class, [
		'name' => $name,
	]);
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

	assertDatabaseHas(AccountGjp2Binding::class, [
		'account_id' => $account->id,
		'gjp2' => app(GeometryDashAlgorithmService::class)->generateGjp2($password)
	]);
});