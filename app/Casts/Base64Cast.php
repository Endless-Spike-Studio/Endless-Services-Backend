<?php

namespace App\Casts;

use Base64Url\Base64Url;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class Base64Cast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes): string
    {
        return $this->cast($value);
    }

    protected function cast(?string $value): string
    {
        if (empty($value)) {
            return (string)$value;
        }

        try {
            Base64Url::decode($value);
            return $value;
        } catch (InvalidArgumentException) {
            return Base64Url::encode($value, true);
        }
    }

    public function set($model, $key, $value, $attributes): string
    {
        return $this->cast($value);
    }
}
