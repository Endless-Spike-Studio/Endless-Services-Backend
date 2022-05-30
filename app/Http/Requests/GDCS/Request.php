<?php

namespace App\Http\Requests\GDCS;

use App\Exceptions\GDCS\RequestAuthorizationFailedException;
use App\Exceptions\GDCS\RequestValidationFailedException;
use App\Models\GDCS\Account;
use App\Models\GDCS\User;
use GDCN\GDAlgorithm\enums\Keys;
use GDCN\GDAlgorithm\GDAlgorithm;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Request extends FormRequest
{
    public Account $account;
    public User $user;

    public function rules(): array
    {
        return [];
    }

    protected function authAccount(): bool
    {
        if (!$this->filled(['accountID', 'gjp'])) {
            return false;
        }

        $account = Account::query()
            ->find(
                $this->get('accountID')
            );

        if (empty($account)) {
            return false;
        }

        $this->account = $account;
        $password = GDAlgorithm::decode(
            $this->get('gjp'),
            Keys::ACCOUNT_PASSWORD->value
        );

        if (!Hash::check($password, $this->account->password)) {
            return false;
        }

        if (empty($this->account->user)) {
            $this->newPlayer();
        }

        $this->user = $this->account->user;
        return true;
    }

    protected function authAccountUsingName(): bool
    {
        if (!$this->filled(['userName', 'password'])) {
            return false;
        }

        $account = Account::query()
            ->whereName(
                $this->get('userName')
            )->first();

        if (empty($account)) {
            return false;
        }

        $this->account = $account;
        if (!Hash::check($this->get('password'), $this->account->password)) {
            return false;
        }

        if (!empty($this->account->user)) {
            $this->user = $this->account->user;
        }

        return true;
    }

    protected function authUser(): bool
    {
        if (!$this->filled(['uuid', 'udid'])) {
            return false;
        }

        $user = User::query()
            ->whereKey(
                $this->get('uuid')
            )->orWhere(
                'uuid',
                $this->get('udid')
            )
            ->where(
                'udid',
                $this->get('udid')
            )->firstOr(
                fn() => $this->newPlayer()
            );

        if (empty($user)) {
            return false;
        }

        $this->user = $user;
        if (!empty($this->user->account)) {
            $this->account = $this->user->account;
        }

        return true;
    }

    public function auth(): bool
    {
        return $this->authAccount()
            || $this->authAccountUsingName()
            || $this->authUser();
    }

    public function newPlayer(): ?User
    {
        $uuid = $this->get(
            'udid',
            Str::uuid()
                ->toString()
        );

        return User::query()
            ->firstOrCreate([
                'uuid' => $this->account->id ?? $uuid
            ], [
                'name' => $this->get('userName', $this->account->name ?? 'Player'),
                'udid' => $uuid
            ]);
    }

    /**
     * @throws RequestAuthorizationFailedException
     */
    protected function failedAuthorization(): void
    {
        throw new RequestAuthorizationFailedException();
    }

    /**
     * @throws RequestValidationFailedException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw (new RequestValidationFailedException)->setValidator($validator);
    }
}
