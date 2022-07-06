<?php

namespace App\Http\Requests\GDCS;

use App\Exceptions\GDCS\RequestAuthorizationFailedException;
use App\Exceptions\GDCS\RequestValidationFailedException;
use App\Models\GDCS\Account;
use App\Models\GDCS\User;
use GDCN\GDAlgorithm\enums\Keys;
use GDCN\GDAlgorithm\GDAlgorithm;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        if (! $this->filled(['accountID', 'gjp'])) {
            return false;
        }

        $accountID = $this->get('accountID');
        $gjp = $this->get('gjp');

        try {
            $this->account = Account::query()
                ->findOrFail($accountID);

            $password = GDAlgorithm::decode($gjp, Keys::ACCOUNT_PASSWORD->value);
            if (! Hash::check($password, $this->account->password)) {
                return false;
            }

            $this->user = $this->account->user ?? $this->newPlayer();

            return ! empty($this->user);
        } catch (ModelNotFoundException) {
            return false;
        }
    }

    protected function authAccountUsingName(): bool
    {
        if (! $this->filled(['userName', 'password'])) {
            return false;
        }

        $name = $this->get('userName');
        $password = $this->get('password');

        try {
            $this->account = Account::query()
                ->whereName($name)
                ->firstOrFail();

            if (! Hash::check($password, $this->account->password)) {
                return false;
            }

            $this->user = $this->account->user ?? $this->newPlayer();

            return ! empty($this->user);
        } catch (ModelNotFoundException) {
            return false;
        }
    }

    protected function authUser(): bool
    {
        if (! $this->filled(['uuid', 'udid'])) {
            return false;
        }

        $uuid = $this->get('uuid');
        $udid = $this->get('udid');

        try {
            $user = User::query()
                ->whereKey($uuid)
                ->orWhere('uuid', $udid)
                ->where('udid', $udid)
                ->firstOrFail();

            $this->user = $user;
            if (! empty($this->user->account)) {
                $this->account = $this->user->account;
            }

            return ! empty($this->user);
        } catch (ModelNotFoundException) {
            $this->user = $this->newPlayer();

            return ! empty($this->user);
        }
    }

    public function auth(): bool
    {
        return $this->authAccount()
            || $this->authAccountUsingName()
            || $this->authUser();
    }

    public function newPlayer(): ?User
    {
        $randomUUID = Str::uuid()
            ->toString();

        $udid = $this->get('udid', $randomUUID);
        $uuid = $this->account->id ?? $udid;
        $name = $this->get('userName', $this->account->name ?? 'Player');

        return User::query()
            ->firstOrCreate(['uuid' => $uuid], ['name' => $name, 'udid' => $udid]);
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
