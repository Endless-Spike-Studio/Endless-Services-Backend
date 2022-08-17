<?php

use App\Enums\Response;
use App\Models\GDCS\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\post;
use function PHPUnit\Framework\assertEqualsIgnoringCase;

uses(RefreshDatabase::class);

test('游戏内注册', function () {
    $faker = fake();
    $unique = $faker->unique();
    $name = $unique->userName;

    $request = post(route('game.gdcs.account.register'), [
        'userName' => $name,
        'password' => $faker->password,
        'email' => $unique->safeEmail,
        'secret' => 'Wmfv3899gc9',
    ]);

    $response = $request->content();
    assertEqualsIgnoringCase(Response::ACCOUNT_REGISTER_SUCCESS->value, $response);
    assertDatabaseHas(Account::class, ['name' => $name]);
});

test('游戏内登录', function () {
    $faker = fake();
    $password = $faker->password;

    $account = Account::factory()
        ->withPassword($password)
        ->createOne();

    $request = post(route('game.gdcs.account.login'), [
        'userName' => $account->name,
        'password' => $password,
        'udid' => $faker->uuid,
        'secret' => 'Wmfv3899gc9',
    ]);

    $response = $request->content();
    assertEqualsIgnoringCase(implode(',', [$account->id, $account->user->id]), $response);
});

test('网页注册', function () {
    assertGuest();

    $faker = fake();
    $unique = $faker->unique();

    $name = $unique->userName;
    $password = $faker->password;

    post(route('gdcs.register.api'), [
        'name' => $name,
        'password' => $password,
        'password_confirmation' => $password,
        'email' => $unique->safeEmail,
    ]);

    assertDatabaseHas(Account::class, ['name' => $name]);
    assertAuthenticated('gdcs');
});

test('网页登录', function () {
    $faker = fake();
    $password = $faker->password;

    $account = Account::factory()
        ->withPassword($password)
        ->createOne();

    post(route('gdcs.login.api'), [
        'name' => $account->name,
        'password' => $password,
    ]);

    assertAuthenticatedAs($account, 'gdcs');
});
