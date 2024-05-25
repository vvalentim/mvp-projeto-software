<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerBR = fake('pt_BR');

        return [
            'name' => "{$fakerBR->firstName()} {$fakerBR->lastName()}",
            'email' => $fakerBR->email(),
            'phone' => $fakerBR->cellPhoneNumber(),
            'subject' => fake()->randomElement(['buy', 'sell', 'question', 'suggestion']),
            'message' => fake()->text(50),
            'status' => 'unverified',
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'verified',
        ]);
    }
}
