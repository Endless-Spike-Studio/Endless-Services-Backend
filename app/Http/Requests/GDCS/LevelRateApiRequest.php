<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class LevelRateApiRequest extends Request
{
    public function rules(): array
    {
        return [
            'stars' => [
                'required',
                'integer',
            ],
            'difficulty' => [
                'required',
                'integer',
                Rule::in([0, 10, 20, 30, 40, 50, 60]),
            ],
            'featured_score' => [
                'required',
                'integer',
            ],
            'epic' => [
                'required',
                'boolean',
            ],
            'coin_verified' => [
                'required',
                'boolean',
            ],
            'demon_difficulty' => [
                'required',
                'integer',
                Rule::in([3, 4, 0, 5, 6]),
            ],
            'auto' => [
                'required',
                'boolean',
            ],
            'demon' => [
                'required',
                'boolean',
            ],
        ];
    }
}
