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
            'CNPJ'        => ['required'],
            'email'       => ['required'],
        ]));

        return to_route('dashboard');
    }
}
