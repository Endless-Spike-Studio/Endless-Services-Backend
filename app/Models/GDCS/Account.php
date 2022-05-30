<?php

namespace App\Models\GDCS;

use App\Enums\GDCS\AccountSettingCommentHistoryState;
use App\Enums\GDCS\AccountSettingFriendRequestState;
use App\Enums\GDCS\AccountSettingMessageState;
use App\Enums\GDCS\ModLevel;
use App\Notifications\GDCS\EmailVerificationNotification;
use Database\Factories\GDCS\AccountFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class Account extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory, MustVerifyEmail, Notifiable, HasRoles;

    protected $table = 'gdcs_accounts';
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $dates = ['email_verified_at'];
    protected $casts = ['mod_level' => ModLevel::class];

    protected static function newFactory(): AccountFactory
    {
        return new AccountFactory();
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'uuid')
            ->withDefault([
                'name' => $this->name,
                'uuid' => $this->id
            ]);
    }

    public function setting(): HasOne
    {
        return $this->hasOne(AccountSetting::class)
            ->withDefault([
                'message_state' => AccountSettingMessageState::ANY,
                'friend_request_state' => AccountSettingFriendRequestState::ANY,
                'comment_history_state' => AccountSettingCommentHistoryState::ANY,
                'youtube_channel' => null,
                'twitch' => null,
                'twitter' => null
            ]);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(AccountComment::class);
    }

    public function sendEmailVerificationNotification(): void
    {
        if (!$this->hasVerifiedEmail()) {
            $this->notify(new EmailVerificationNotification);
        }
    }

    public function getFriendAccountIdsWithSelfAttribute(): array
    {
        return $this->friends()
            ->get()
            ->map(function (AccountFriend $friend) {
                return $friend->account_id === $this->id ? $friend->friend_account_id : $friend->account_id;
            })->push($this->id)
            ->toArray();
    }

    public function friends(): AccountFriend|Builder
    {
        return AccountFriend::query()
            ->orWhere([
                'account_id' => $this->id,
                'friend_account_id' => $this
            ]);
    }

    public function getFriendUserIdsWithSelfAttribute(): array
    {
        return $this->friends()
            ->get()
            ->map(function (AccountFriend $friend) {
                return $friend->account_id === $this->id ? $friend->friend_account->user->id : $friend->account->user->id;
            })->push($this->user->id)
            ->toArray();
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(AccountBlock::class, 'account_id');
    }

    public function links(): HasMany
    {
        return $this->hasMany(AccountLink::class);
    }

    public function tempLevelUploadAccesses(): HasMany
    {
        return $this->hasMany(TempLevelUploadAccess::class);
    }

    public function levelTransferRecords(): HasMany
    {
        return $this->hasMany(LevelTransferRecord::class);
    }

    public function uploadedCustomSongs(): HasMany
    {
        return $this->hasMany(CustomSong::class);
    }

    public function isBlock(int $targetAccountID): bool
    {
        return AccountBlock::query()
            ->where('account_id', $this->id)
            ->where('target_account_id', $targetAccountID)
            ->where('account_id', $targetAccountID)
            ->where('target_account_id', $this->id)
            ->exists();
    }

    public function getNewMessageCountAttribute(): int
    {
        return AccountMessage::query()
            ->where([
                'target_account_id' => $this->id,
                'new' => true
            ])->count();
    }

    public function getNewFriendRequestCountAttribute(): int
    {
        return AccountFriendRequest::query()
            ->where([
                'target_account_id' => $this->id,
                'new' => true
            ])->count();
    }

    public function getNewFriendCountAttribute(): int
    {
        return AccountFriend::query()
            ->where([
                'account_id' => $this->id,
                'new' => true
            ])
            ->union(
                AccountFriend::query()
                    ->where([
                        'friend_account_id' => $this->id,
                        'friend_new' => true
                    ])->toBase()
            )->count();
    }

    public function isFriendWith(int $targetAccountID): bool
    {
        return AccountFriend::findBetween($this->id, $targetAccountID)->exists();
    }

    public function setPasswordAttribute(?string $password): void
    {
        if (!empty($password)) {
            $this->attributes['password'] = Hash::make($password);
        }
    }
}
