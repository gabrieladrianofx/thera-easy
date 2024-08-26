<?php

use App\Http\Controllers\{ClinicController, DashboardController, ProfileController};
use Illuminate\Support\Facades\{Auth, Route};

Route::get('/', function () {
    if(app()->isLocal()) {
        Auth::loginUsingId(1);

        return to_route('dashboard');
    }

    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/clinic/store', [ClinicController::class, 'store'])->name('clinic.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
