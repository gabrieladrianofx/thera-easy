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

    public function update(Clinic $clinic): RedirectResponse
    {
        request()->validate([
            'name_clinic' => ['required'],
        ]);

        $clinic->update([
            'name_clinic' => request()->name_clinic,
            'CNPJ'        => request()->CNPJ,
            'email'       => request()->email,
        ]);

        return to_route('dashboard');
    }
}
