<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('pt_BR');
        $prefixNeighborhood = ['Jardim', 'Vila', 'Bairro', 'Núcleo', 'Parque', ''];
        $suffixNeighborhood = ['Oficinas', 'Neves', 'Bela Vista', 'Boa Vista', 'Esplanada', 'Santa Paula'];

        $neighborhood = trim("{$faker->randomElement($prefixNeighborhood)} {$faker->randomElement($suffixNeighborhood)}");
        $registryType = $faker->randomElement(['F', 'J']);

        [$name, $registry, $identity] = match ($registryType) {
            'F' => ["{$faker->firstName()} {$faker->lastName()}", $faker->cpf(false), $faker->rg(false)],
            'J' => [$faker->company(), $faker->cnpj(false), ''],
        };

        return [
            'type' => $registryType,
            'name' => $name,
            'num_registry' => $registry,
            'num_identity' => $identity,
            'birthdate' => $faker->date(),

            'zip_code' => preg_replace('/\D/', '', $faker->postcode()),
            'address_state' => $faker->stateAbbr(),
            'address_city' => $faker->city(),
            'address_neighborhood' => $neighborhood,
            'address_street' => $faker->streetName(),
            'address_number' => $faker->secondaryAddress(),

            'phone_1' => $faker->cellPhoneNumber(),
            'phone_2' => $faker->cellPhoneNumber()
        ];
    }
}
