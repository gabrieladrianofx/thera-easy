<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'clinics' => Clinic::all(),
        ]);
    }
}
