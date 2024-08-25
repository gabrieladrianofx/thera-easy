<?php

use App\Models\User;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, post};

it('should be Able to create a clinic successfully with valid data', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('clinic.store', [
        'name_clinic' => 'medical clinic one',
        'CNPJ'        => '96180855000110',
        'email'       => 'medicalclinicone@example.com',
    ]))->assertRedirect(route('dashboard'));

    assertDatabaseCount('clinics', 1);
    assertDatabaseHas('clinics', [
        'name_clinic' => 'medical clinic one',
        'CNPJ'        => '96180855000110',
        'email'       => 'medicalclinicone@example.com',
    ]);
});

it('should fail when trying to create a clinic without providing the name_clinic, CNPJ and email', function () {
    $user = User::factory()->create();
    actingAs($user);

    $request = post(route('clinic.store', [
        'name_clinic' => '',
        'CNPJ'        => '',
        'email'       => '',
    ]));

    $request->assertSessionHasErrors(['name_clinic', 'CNPJ', 'email']);
    assertDatabaseCount('clinics', 0);
});

it('should fail to create a clinic with a duplicate CNPJ.', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('clinic.store', [
        'name_clinic' => 'medical clinical one',
        'CNPJ'        => '12345678912345',
        'email'       => 'medicalclinicone@example.com',
    ]));

    $request = post(route('clinic.store', [
        'name_clinic' => 'medical clinical two',
        'CNPJ'        => '12345678912345',
        'email'       => 'medicalclinictwo@example.com',
    ]));

    $request->assertSessionHasErrors('CNPJ');
    assertDatabaseCount('clinics', 1);
});

it('should fail to create a clinic with a CNPJ not equal to 14 characters', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = post(route('clinic.store', [
        'name_clinic' => 'medical clinical one',
        'CNPJ'        => str_repeat('1', 15),
        'email'       => 'medicalclinicone@example.com',
    ]));

    $response->assertSessionHasErrors('CNPJ');
    assertDatabaseCount('clinics', 0);
});

it('should fail to create a clinic with a duplicate email.', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('clinic.store', [
        'name_clinic' => 'medical clinical one',
        'CNPJ'        => '12345678912345',
        'email'       => 'medicalclinicone@example.com',
    ]));

    $request = post(route('clinic.store', [
        'name_clinic' => 'medical clinical two',
        'CNPJ'        => '98745632112345',
        'email'       => 'medicalclinicone@example.com',
    ]));

    $request->assertSessionHasErrors('email');
    assertDatabaseCount('clinics', 1);
});
