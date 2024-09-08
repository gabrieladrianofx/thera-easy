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

it('should not be updated while there exists a duplicate CNPJ.', function () {
    $user   = User::factory()->create();
    $clinic = Clinic::factory()->create(['CNPJ' => str_repeat('5', 14)]);

    actingAs($user);

    put(route('clinic.update', $clinic), ['CNPJ' => str_repeat('5', 14)])->assertSessionHasErrors('CNPJ');

    assertDatabaseCount('clinics', 1);
});

it('should not be updated until the CNPJ size is between 13 and 15 characters', function () {
    $user   = User::factory()->create();
    $clinic = Clinic::factory()->create(['CNPJ' => str_repeat('4', 14)]);

    actingAs($user);

    put(
        route('clinic.update', $clinic),
        ['CNPJ' => str_repeat('5', 14)]
    )->assertRedirect();

    put(
        route('clinic.update', $clinic),
        ['CNPJ' => str_repeat('5', 13)]
    )->assertSessionHasErrors('CNPJ');

    put(
        route('clinic.update', $clinic),
        ['CNPJ' => str_repeat('5', 15)]
    )->assertSessionHasErrors('CNPJ');

    assertDatabaseCount('clinics', 1);
});
