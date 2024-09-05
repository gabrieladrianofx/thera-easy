<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ClinicController extends Controller
{
    public function edit(Clinic $clinic): View
    {
        return view('clinic.edit', compact('clinic'));
    }

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
