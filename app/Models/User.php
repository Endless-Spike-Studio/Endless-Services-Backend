<?php

namespace App\Models;

use App\Notifications\EmailVerificationNotification;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasApiTokens, HasFactory, Notifiable, MustVerifyEmail;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $dates = ['email_verified_at'];

    public function sendEmailVerificationNotification(): void
    {
        if (!$this->hasVerifiedEmail()) {
            $this->notify(new EmailVerificationNotification);
        }
    }

    public function setPasswordAttribute(?string $password): void
    {
        if (!empty($password)) {
            $this->attributes['password'] = Hash::make($password);
        }
    }
}
