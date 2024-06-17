<?php

namespace Database\Factories;

use App\Enums\FollowUpStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FollowUp>
 */
class FollowUpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'status' => fake()->randomElement(FollowUpStatus::values()),
            'status' => FollowUpStatus::Lead,
        ];
    }
}
