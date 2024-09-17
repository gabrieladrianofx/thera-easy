<?php

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
