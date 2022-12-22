<?php

namespace LifeSpikes\PhpBeam\Laravel\ValidationRules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    public function passes($attribute, $value): bool
    {
        return validator([
            $attribute => $value,
        ], [
            $attribute => 'phone:US',
        ])->passes();
    }

    public function message(): string
    {
        return 'Not a valid U.S. SMS-enabled phone number';
    }
}

