<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clinic>
 */
class ClinicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_clinic' => fake()->company(),
            'CNPJ'        => $this->generateRandomCNPJ(),
            'email'       => fake()->email(),
        ];
    }

    /**
     * Generate a random CNPJ number as a 14-digit integer string.
     *
     * @return string
     */
    private function generateRandomCNPJ(): string
    {
        $cnpj = '';

        // Generate 14 random digits
        for ($i = 0; $i < 14; $i++) {
            $cnpj .= random_int(0, 9);
        }

        return $cnpj;
    }
}
