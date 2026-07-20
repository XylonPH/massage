<?php

namespace App\Rules;

use App\Support\Directory\PublicContactLink;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PublicContactUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! (new PublicContactLink)->allows(trim((string) $value))) {
            $fail(__('spa.contact_url_invalid'));
        }
    }
}
