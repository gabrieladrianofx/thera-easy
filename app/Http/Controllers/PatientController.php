<?php

namespace App\Http\Controllers;

use App\Enums\{CivilStatus, GenderStatus};
use App\Models\Patient;
use App\Rules\SameCPFRule;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function store(): RedirectResponse
    {
        Patient::query()->create(request()->validate([
            'CPF'            => ['required', 'size:11', new SameCPFRule()],
            'name_patient'   => 'required|between:3,255',
            'gender'         => ['nullable', Rule::enum(GenderStatus::class)],
            'civil_status'   => ['nullable', Rule::enum(CivilStatus::class)],
            'contact_number' => [
                'nullable',
                'string',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!preg_match('/^\(?\d{2}\)?\s?\d{4,5}-\d{4}$|^\d{11}$/', $value)) {
                        $fail("The {$attribute} field must be a valid phone number in the format (XX) XXXX-XXXX, (XX) XXXXX-XXXX, or XXXXXXX-XXXX.");
                    }
                },
            ],
        ]));

        return back();
    }
}
