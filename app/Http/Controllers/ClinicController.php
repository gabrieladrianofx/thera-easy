<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\RedirectResponse;

class ClinicController extends Controller
{
    public function store(): RedirectResponse
    {
        Clinic::query()->create(request()->validate([
            'name_clinic' => ['required'],
            'CNPJ'        => ['required', 'unique:clinics', 'size:14'],
            'email'       => ['required', 'unique:clinics', 'email'],
        ]));

        return to_route('dashboard');
    }
}
