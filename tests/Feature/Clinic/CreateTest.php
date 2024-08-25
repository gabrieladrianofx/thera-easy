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
