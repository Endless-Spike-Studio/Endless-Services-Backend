<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class UserVerifyApiRequest extends Request
{
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        [$id, $email] = explode(
            '|',
            Crypt::decryptString(
                $this->route('_')
            )
        );
        return $user->id === (int)$id && $user->getEmailForVerification() === $email;
    }
}
