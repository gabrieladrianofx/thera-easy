<?php

use App\Models\{Clinic, User};

use function Pest\Laravel\{actingAs, get};

it('should be able to open a clinic to edit', function () {
    $user   = User::factory()->create();
    $clinic = Clinic::factory()->create();

    actingAs($user);

    get(route('clinic.edit', $clinic))->assertSuccessful();
});

it('should return a view', function () {
    $user   = User::factory()->create();
    $clinic = Clinic::factory()->create();

    actingAs($user);

    get(route('clinic.edit', $clinic))->assertViewIs('clinic.edit');
});
