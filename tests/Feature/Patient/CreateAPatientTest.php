<?php

use App\Enums\{CivilStatus, GenderStatus};
use App\Models\User;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, post};

it('should be possible to register a patient only when their name has a minimum of 3 and a maximum of 255 characters.', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('patient.store', [
        'CPF'          => str_repeat('1', 11),
        'name_patient' => str_repeat('*', 150),
    ]))->assertRedirect();

    post(route('patient.store', [
        'CPF'          => str_repeat('1', 11),
        'name_patient' => str_repeat('*', 2),
    ]))->assertSessionHasErrors(['name_patient' => __('validation.between.string', ['min' => 3, 'max' => 255, 'attribute' => 'name patient'])]);

    post(route('patient.store', [
        'CPF'          => str_repeat('1', 11),
        'name_patient' => str_repeat('*', 256),
    ]))->assertSessionHasErrors(['name_patient' => __('validation.between.string', ['min' => 3, 'max' => 255, 'attribute' => 'name patient'])]);

    assertDatabaseHas('patients', [
        'name_patient' => str_repeat('*', 150),
    ]);
    assertDatabaseCount('patients', 1);
});

it('should be able to register patients only when CPF contains exactly 11 digits.', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('patient.store', [
        'CPF'          => str_repeat('1', 11),
        'name_patient' => str_repeat('*', 150),
    ]))->assertRedirect();

    post(route('patient.store', [
        'CPF'          => str_repeat('1', 12),
        'name_patient' => str_repeat('*', 150),
    ]))->assertSessionHasErrors(['CPF' => __('validation.size.string', ['size' => 11, 'attribute' => 'c p f'])]);

    assertDatabaseHas('patients', ['CPF' => str_repeat('1', 11)]);
    assertDatabaseCount('patients', 1);
});

it('CPF should be unique', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('patient.store', [
        'CPF'          => str_repeat('1', 11),
        'name_patient' => str_repeat('*', 150),
    ]))->assertRedirect();

    post(route('patient.store', [
        'CPF'          => str_repeat('1', 11),
        'name_patient' => str_repeat('*', 150),
    ]))->assertSessionHasErrors(['CPF' => 'There is already a patient registered with this CPF.']);

    assertDatabaseHas('patients', ['CPF' => str_repeat('1', 11)]);
    assertDatabaseCount('patients', 1);
});

it('should allow registering a patient when the gender field is informed with the correct data', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('patient.store', [
        'CPF'          => str_repeat('1', 11),
        'name_patient' => str_repeat('*', 150),
        'gender'       => GenderStatus::Female->value,
    ]))->assertRedirect();

    post(route('patient.store', [
        'CPF'          => str_repeat('1', 11),
        'name_patient' => str_repeat('*', 150),
        'gender'       => 'homosexual',
    ]))->assertSessionHasErrors('gender');

    assertDatabaseHas('patients', [
        'gender' => GenderStatus::Female->value,
    ]);
    assertDatabaseCount('patients', 1);
});

it('should allow registering a patient when the marital status field is informed with the correct data', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('patient.store', [
        'CPF'          => str_repeat('1', 11),
        'name_patient' => str_repeat('*', 150),
        'civil_status' => CivilStatus::Single->value,
    ]))->assertRedirect();

    post(route('patient.store', [
        'CPF'          => str_repeat('1', 11),
        'name_patient' => str_repeat('*', 150),
        'civil_status' => 'others_civil_status',
    ]))->assertSessionHasErrors('civil_status');

    assertDatabaseHas('patients', [
        'civil_status' => CivilStatus::Single->value,
    ]);
    assertDatabaseCount('patients', 1);
});

it('should allow patient registration when the contact field is filled in and regex is validated', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('patient.store', [
        'CPF'            => str_repeat('1', 11),
        'name_patient'   => str_repeat('*', 150),
        'contact_number' => '84932129632',
    ]))->assertRedirect();

    post(route('patient.store', [
        'CPF'            => str_repeat('2', 11),
        'name_patient'   => str_repeat('*', 150),
        'contact_number' => '98743256',
    ]))->assertSessionHasErrors(['contact_number' => 'The contact_number field must be a valid phone number in the format (XX) XXXX-XXXX, (XX) XXXXX-XXXX, or XXXXXXX-XXXX.']);

    assertDatabaseHas('patients', ['contact_number' => '84932129632']);
});
