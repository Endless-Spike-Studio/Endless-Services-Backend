<?php

namespace App\Http\Requests\GDCS\Game;

use App\Enums\GDCS\Game\Algorithm\Keys;
use App\Enums\GDCS\Game\Algorithm\Salts;
use App\Models\GDCS\Account;
use App\Services\Game\AlgorithmService;
use Illuminate\Validation\Rule;

class UserScoreUpdateRequest extends Request
{
    public function authorize(): bool
    {
        return $this->auth() && !empty($this->user) && $this->validateSeed2();
    }

    protected function validateSeed2(): bool
    {
        return hash_equals(
            AlgorithmService::encode($this->get('accountID', 0) . $this->get('userCoins') . $this->get('demons') . $this->get('stars') . $this->get('coins') . $this->get('iconType') . $this->get('icon') . $this->get('diamonds') . $this->get('accIcon') . $this->get('accShip') . $this->get('accBall') . $this->get('accBird') . $this->get('accDart') . $this->get('accRobot') . $this->get('accGlow') . $this->get('accSpider') . $this->get('accExplosion') . Salts::USER_PROFILE->value, Keys::USER_PROFILE->value),
            $this->get('seed2')
        );
    }

    public function rules(): array
    {
        return [
            'gameVersion' => [
                'required',
                'integer',
            ],
            'binaryVersion' => [
                'required',
                'integer',
            ],
            'gdw' => [
                'required',
                'boolean',
            ],
            'accountID' => [
                'sometimes',
                'exclude_if:accountID,0',
                'required',
                'integer',
                Rule::exists(Account::class, 'id'),
            ],
            'gjp' => [
                'required_with:accountID',
                'nullable',
                'string',
            ],
            'uuid' => [
                'required_without:accountID',
                'integer',
            ],
            'udid' => [
                'required_with:uuid',
                'string',
            ],
            'userName' => [
                'required',
                'string',
            ],
            'stars' => [
                'required',
                'integer',
            ],
            'demons' => [
                'required',
                'integer',
            ],
            'diamonds' => [
                'required',
                'integer',
            ],
            'icon' => [
                'required',
                'integer',
            ],
            'color1' => [
                'required',
                'integer',
            ],
            'color2' => [
                'required',
                'integer',
            ],
            'iconType' => [
                'required',
                'integer',
            ],
            'coins' => [
                'required',
                'integer',
            ],
            'userCoins' => [
                'required',
                'integer',
            ],
            'special' => [
                'required',
                'integer',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
            'accIcon' => [
                'required',
                'integer',
            ],
            'accShip' => [
                'required',
                'integer',
            ],
            'accBall' => [
                'required',
                'integer',
            ],
            'accBird' => [
                'required',
                'integer',
            ],
            'accDart' => [
                'required',
                'integer',
            ],
            'accRobot' => [
                'required',
                'integer',
            ],
            'accGlow' => [
                'required',
                'integer',
            ],
            'accSpider' => [
                'required',
                'integer',
            ],
            'accExplosion' => [
                'required',
                'integer',
            ],
            'seed' => [
                'required',
                'string',
            ],
            'seed2' => [
                'required',
                'string',
            ],
        ];
    }
}
