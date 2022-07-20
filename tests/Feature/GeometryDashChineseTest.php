<?php

use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\get;
use function Pest\Laravel\isAuthenticated;
use function Pest\Laravel\post;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

uses(RefreshDatabase::class);

test('注册', function () {
    Notification::fake();
    Notification::assertNothingSent();

    $faker = fake();
    $password = $faker->password();

    $request = post(
        route('register.api'),
        [
            'name' => $faker->userName(),
            'email' => $faker->safeEmail(),
            'password' => $password,
            'password_confirmation' => $password
        ]
    );

    $request->assertSessionHas('messages', [
        [
            'options' => [
                'type' => 'success'
            ],
            'content' => __('messages.register_success')
        ]
    ]);

    $request->assertRedirect();
    Notification::assertSentTo(
        User::first(),
        EmailVerificationNotification::class
    );
});

test('登录', function () {
    $faker = fake();
    $password = $faker->password();

    $user = User::factory()
        ->withPassword($password)
        ->createOne();

    $request = post(
        route('login.api'),
        [
            'name' => $user->name,
            'password' => $password
        ]
    );

    $request->assertSessionHas('messages', [
        [
            'options' => [
                'type' => 'success'
            ],
            'content' => __('messages.welcome_back', [
                'name' => $user->name
            ])
        ]
    ]);

    $request->assertRedirect();
    assertAuthenticatedAs($user);
});

test('登录失败的反馈', function () {
    $faker = fake();
    $password = $faker->password();

    $user = User::factory()
        ->withPassword($password)
        ->createOne();

    $request = post(
        route('login.api'),
        [
            'name' => $user->name,
            'password' => $password . $faker->randomAscii()
        ]
    );

    $request->assertSessionHas('messages', [
        [
            'options' => [
                'type' => 'error'
            ],
            'content' => __('messages.login_failed')
        ]
    ]);

    $request->assertRedirect();
});

test('邮箱验证', function () {
    $user = User::factory()
        ->unverified()
        ->createOne();

    assertFalse(
        $user->hasVerifiedEmail()
    );

    actingAs($user)->get(
        app(EmailVerificationNotification::class)
            ->toMail($user)
            ->actionUrl
    )->assertSessionHas('messages', [
        [
            'options' => [
                'type' => 'success'
            ],
            'content' => __('messages.email_verified')
        ]
    ])->assertRedirect();

    assertTrue(
        $user->hasVerifiedEmail()
    );
});

test('邮箱验证不重复', function () {
    $user = User::factory()
        ->createOne();

    assertTrue(
        $user->hasVerifiedEmail()
    );

    actingAs($user)->get(
        app(EmailVerificationNotification::class)
            ->toMail($user)
            ->actionUrl
    )->assertSessionHas('messages', [
        [
            'options' => [
                'type' => 'error'
            ],
            'content' => __('messages.email_already_verified')
        ]
    ])->assertRedirect();
});

test('登出', function () {
    $user = User::factory()
        ->unverified()
        ->createOne();

    actingAs($user);
    assertTrue(
        isAuthenticated()
    );

    get(
        route('user.logout.api')
    )->assertSessionHas('messages', [
        [
            'options' => [
                'type' => 'success'
            ],
            'content' => __('messages.logout_success')
        ]
    ])->assertRedirect();

    assertFalse(
        isAuthenticated()
    );
});

test('重发验证邮件', function () {
    Notification::fake();
    Notification::assertNothingSent();

    $user = User::factory()
        ->unverified()
        ->createOne();

    assertFalse(
        $user->hasVerifiedEmail()
    );

    actingAs($user)->post(
        route('user.resendEmailVerification.api')
    )->assertSessionHas('messages', [
        [
            'options' => [
                'type' => 'success'
            ],
            'content' => __('messages.verification_sent')
        ]
    ])->assertRedirect();

    Notification::assertSentTo(
        $user,
        EmailVerificationNotification::class
    );
});

test('重发验证邮件不重复', function () {
    $user = User::factory()
        ->createOne();

    assertTrue(
        $user->hasVerifiedEmail()
    );

    actingAs($user)->post(
        route('user.resendEmailVerification.api')
    )->assertSessionHas('messages', [
        [
            'options' => [
                'type' => 'error'
            ],
            'content' => __('messages.email_already_verified')
        ]
    ])->assertRedirect();
});

test('重发验证邮件限流', function () {
    Notification::fake();

    $user = User::factory()
        ->unverified()
        ->createOne();

    assertFalse(
        $user->hasVerifiedEmail()
    );

    RateLimiter::hit(
        "gdcn:resendEmailVerification:$user->id"
    );

    actingAs($user)->post(
        route('user.resendEmailVerification.api')
    )->assertSessionHas('messages', [
        [
            'options' => [
                'type' => 'error'
            ],
            'content' => __('messages.too_fast')
        ]
    ])->assertRedirect();

    Notification::assertNothingSent();
});

test('更新设置', function () {
    Notification::fake();
    Notification::assertNothingSent();

    $faker = fake();
    $password = $faker->password();

    $user = User::factory()
        ->unverified()
        ->createOne();

    actingAs($user)->patch(
        route('user.setting.update.api'),
        [
            'name' => $faker->name(),
            'email' => $faker->safeEmail(),
            'password' => $password,
            'password_confirmation' => $password
        ]
    )->assertSessionHas('messages', [
        [
            'options' => [
                'type' => 'success'
            ],
            'content' => __('messages.profile_updated')
        ]
    ])->assertRedirect();

    $user->fresh();
    Notification::assertSentTo(
        $user,
        EmailVerificationNotification::class
    );

    assertTrue(
        Hash::check($password, $user->password)
    );
});
