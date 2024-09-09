<?php

use App\Models\{Clinic, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, put};
use function PHPUnit\Framework\assertSame;

it('should be able to update a clinic', function () {
    $user   = User::factory()->create();
    $clinic = Clinic::factory()->create();

    actingAs($user);

    put(route('clinic.update', $clinic), [
        'name_clinic' => 'Clinical OffShore',
        'CNPJ'        => str_repeat('5', 14),
        'email'       => 'clinicaloffshore@example.com',
    ])->assertRedirect(route('clinic.index'));

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
    $user = User::factory()->create();

    Clinic::factory()->create(['CNPJ' => str_repeat('6', 14)]);
    $clinicToBeUpdated = Clinic::factory()->create(['CNPJ' => str_repeat('5', 14)]);

    actingAs($user);

    put(
        route('clinic.update', $clinicToBeUpdated),
        ['CNPJ' => str_repeat('6', 14)]
    )->assertSessionHasErrors('CNPJ');

    assertDatabaseHas('clinics', [
        'id'   => $clinicToBeUpdated->id,
        'CNPJ' => str_repeat('5', 14),
    ]);
    assertSame(str_repeat('5', 14), $clinicToBeUpdated->CNPJ);
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

it('should not be updated when a duplicate email exists.', function () {
    $user = User::factory()->create();
    Clinic::factory()->create(['email' => 'jon.doe@example.com']);
    $clinicToBeUpdated = Clinic::factory()->create(['email' => 'maria.doe@example.com']);

    actingAs($user);

    put(
        route('clinic.update', $clinicToBeUpdated),
        ['email' => 'jon.doe@example.com']
    )->assertSessionHasErrors('email');

    assertDatabaseHas('clinics', [
        'id'    => $clinicToBeUpdated->id,
        'email' => ['email' => 'maria.doe@example.com'],
    ]);
    assertSame('maria.doe@example.com', $clinicToBeUpdated->email);
});

it('should not update when the email is not of the email type.', function () {
    $user   = User::factory()->create();
    $clinic = Clinic::factory()->create(['email' => 'jon.doe@example.com']);

    actingAs($user);

    put(
        route('clinic.update', $clinic),
        ['email' => 'jon.doeexample.com']
    )->assertSessionHasErrors('email');

    assertDatabaseCount('clinics', 1);
});
