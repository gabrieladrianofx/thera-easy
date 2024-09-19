<?php

namespace App\Rules;

use App\Models\Patient;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SameCPFRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->validationRule($value)) {
            $fail('There is already a patient registered with this CPF.');
        }
    }

    private function validationRule(string $value): bool
    {
        return Patient::whereCpf($value)->exists();
    }
}
