<?php

use App\Models\{Clinic, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, put};

it('should be able to update a clinic', function () {
    $user   = User::factory()->create();
    $clinic = Clinic::factory()->create();

    actingAs($user);

    put(route('clinic.update', $clinic), [
        'name_clinic' => 'Clinical OffShore',
        'CNPJ'        => str_repeat('5', 14),
        'email'       => 'clinicaloffshore@example.com',
    ])->assertRedirect(route('dashboard'));

    $clinic->refresh();

    expect($clinic->only(['name_clinic', 'CNPJ', 'email']))->toBe([
        'name_clinic' => 'Clinical OffShore',
        'CNPJ'        => str_repeat('5', 14),
        'email'       => 'clinicaloffshore@example.com',
    ]);
});

it('should not be updated while name_clinic is empty.', function () {
    $user   = User::factory()->create();
    $clinic = Clinic::factory()->create(['name_clinic' => '']);

    actingAs($user);

    put(route('clinic.update', $clinic))->assertSessionHasErrors('name_clinic');

    assertDatabaseCount('clinics', 1);
});
