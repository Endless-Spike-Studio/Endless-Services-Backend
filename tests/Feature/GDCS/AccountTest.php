<?php

namespace Tests\Feature\GDCS;

use App\Models\GDCS\Account;
use App\Notifications\GDCS\EmailVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testRegister(): void
    {
        Notification::fake();
        Notification::assertNothingSent();

        $request = $this->post(route('game.gdcs.account.register'), [
            'userName' => $this->faker->userName(),
            'password' => $this->faker->password(),
            'email' => $this->faker->safeEmail(),
            'secret' => 'Wmfv3899gc9'
        ]);

        $request->assertOk();
        $this->assertEquals(
            \App\Enums\Response::ACCOUNT_REGISTER_SUCCESS->value,
            $request->getContent()
        );

        Notification::assertSentTo(
            Account::first(),
            EmailVerificationNotification::class
        );
    }

    public function testLogin(): void
    {
        $password = $this->faker->password();

        $account = Account::factory()
            ->withPassword($password)
            ->createOne();

        $request = $this->post(route('game.gdcs.account.login'), [
            'userName' => $account->name,
            'password' => $password,
            'udid' => $this->faker->uuid(),
            'secret' => 'Wmfv3899gc9'
        ]);

        $request->assertOk();
        $this->assertEquals(
            implode(',', [$account->id, $account->user->id]),
            $request->getContent()
        );
    }
}
