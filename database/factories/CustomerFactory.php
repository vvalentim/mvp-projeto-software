<?php

namespace Database\Factories;

use App\Enums\MaritalStatus;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
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
            'marital_status' => MaritalStatus::Nullable,
            'person_id' => Person::factory()
        ];
    }
}
