<?php

namespace Database\Factories;

use App\Enums\LeadStatus;
use App\Models\RealEstate;
use App\Models\User;
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
        $faker = fake('pt_BR');

        return [
            'name' => "{$faker->firstName()} {$faker->lastName()}",
            'email' => $faker->email(),
            'phone' => $faker->cellPhoneNumber(),
            'subject' => fake()->sentence(3),
            'message' => fake()->text(50),
            'status' => LeadStatus::Unverified,
            'real_estate_id' => RealEstate::factory(),
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LeadStatus::Verified,
        ]);
    }

    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LeadStatus::Assigned,
            'user_id' => User::factory(),
        ]);
    }
}
