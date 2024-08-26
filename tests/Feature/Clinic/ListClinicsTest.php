<?php

use App\Models\{Clinic, User};

use function Pest\Laravel\{actingAs, get};

it('should list all the clinics', function () {
    $user    = User::factory()->create();
    $clinics = Clinic::factory()->count(5)->create();

    actingAs($user);

    $response = get(route('dashboard'));

    foreach ($clinics as $clinic) {
        $response->assertSee([
            'name_clinic' => $clinic->name_clinic,
            'CNPJ'        => $clinic->CNPJ,
        ]);
    }
});
