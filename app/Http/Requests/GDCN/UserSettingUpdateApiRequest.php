<?php

namespace App\Http\Requests\GDCN;

use App\Http\Requests\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserSettingUpdateApiRequest extends Request
{
    public function rules(): array
    {
        $userID = Auth::id();

        return [
            'name' => [
                'required',
                'string',
                Rule::unique(User::class)->ignore($userID),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique(User::class)->ignore($userID),
            ],
            'password' => [
                'exclude_without:password',
                'string',
                'confirmed',
            ],
        ];
    }
}
