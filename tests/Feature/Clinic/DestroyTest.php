<?php

use App\Models\{Clinic, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseMissing, delete};

it('should be able to destroy a clinic', function () {
    $user   = User::factory()->create();
    $clinic = Clinic::factory()->create();

    actingAs($user);

    delete(route('clinic.destroy', $clinic->id))->assertRedirect();

    assertDatabaseCount('clinics', 0);
    assertDatabaseMissing('clinics', ['id' => $clinic->id]);
});
