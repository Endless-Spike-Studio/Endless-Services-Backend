<?php

namespace App\Http\Requests\GDCS;

use Illuminate\Support\Facades\Crypt;

class AccountVerifyRequest extends Request
{
    public function authorize(): bool
    {
        $user = $this->user('gdcs');

        [$id, $email] = explode(
            '|',
            Crypt::decryptString(
                $this->get('_')
            )
        );

        return $user->id === (int)$id && $user->getEmailForVerification() === $email;
    }
}
