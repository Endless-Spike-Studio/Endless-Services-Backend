<?php

namespace App\Models\GDCS;

use Database\Factories\GDCS\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'gdcs_users';

    protected $fillable = ['name', 'uuid', 'udid'];

    protected static function newFactory(): UserFactory
    {
        return new UserFactory();
    }

    public function ban(): HasOne
    {
        return $this->hasOne(BannedUser::class, 'user_id')
            ->withDefault([
                'login_ban' => false,
                'comment_ban' => false,
                'expires_at' => now(),
                'reason' => null,
            ]);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uuid');
    }

    public function score(): HasOne
    {
        return $this->hasOne(UserScore::class)
            ->withDefault([
                'stars' => 0,
                'demons' => 0,
                'diamonds' => 0,
                'coins' => 0,
                'user_coins' => 0,
                'creator_points' => 0,
                'icon' => 0,
                'color1' => 0,
                'color2' => 3,
                'icon_type' => 0,
                'special' => 0,
                'acc_glow' => 0,
                'acc_explosion' => 0,
                'acc_icon' => 0,
                'acc_ship' => 0,
                'acc_ball' => 0,
                'acc_bird' => 0,
                'acc_dart' => 0,
                'acc_robot' => 0,
            ]);
    }

    public function reward(): HasOne
    {
        return $this->hasOne(UserDailyChest::class)
            ->withDefault([
                'small_count' => 0,
                'big_count' => 0,
            ]);
    }

    public function levels(): HasMany
    {
        return $this->hasMany(Level::class, 'user_id');
    }
}
