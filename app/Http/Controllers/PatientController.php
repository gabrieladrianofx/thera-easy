<?php

namespace App\Http\Controllers;

use App\Enums\{CivilStatus, GenderStatus};
use App\Models\Patient;
use App\Rules\SameCPFRule;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function store(): RedirectResponse
    {
        $validatedPatient = request()->validate([
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
        ]);

        $validatedAddress = request()->validate([
            'street_address' => 'required|min:10',
            'street_number'  => 'required|min:2|max:7',
            'district'       => 'required',
            'city'           => 'required',
        ]);

        DB::transaction(function () use ($validatedPatient, $validatedAddress) {
            $patient = Patient::query()->create($validatedPatient);
            $patient->addresses()->create($validatedAddress);
        });

        return back();
    }
}
