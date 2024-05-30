<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RealEstate>
 */
class RealEstateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('pt_BR');
        $realStateTypes = ['Casa', 'Apartamento', 'Sobrado', 'Lote', 'Casa em condomínio'];
        $prefixNeighborhood = ['Jardim', 'Vila', 'Bairro', 'Núcleo', 'Parque', ''];
        $suffixNeighborhood = ['Oficinas', 'Neves', 'Bela Vista', 'Boa Vista', 'Esplanada', 'Santa Paula'];

        $neighborhood = trim("{$faker->randomElement($prefixNeighborhood)} {$faker->randomElement($suffixNeighborhood)}");
        $type = $faker->randomElement($realStateTypes);
        $title = trim("{$type} {$faker->randomElement($suffixNeighborhood)}");

        $areaTotal = $faker->numberBetween(100, 2000);
        $areaBuilt = $faker->numberBetween(10, $areaTotal);

        $taxCondominium = in_array($type, ['Apartamento', 'Casa em condomínio'])
            ? $faker->randomFloat(2, 100, 10000)
            : 0.00;

        return [
            'zip_code' => preg_replace('/\D/', '', $faker->postcode()),
            'address_state' => $faker->stateAbbr(),
            'address_city' => $faker->city(),
            'address_neighborhood' => $neighborhood,
            'address_street' => $faker->streetName(),
            'address_number' => $faker->secondaryAddress(),

            'type' => $type,
            'title' => $title,
            'description' => $faker->text(50),
            'area_total' => $areaTotal,
            'area_built' => $areaBuilt,
            'num_rooms' => $faker->numberBetween(1, 9),
            'num_suite' => $faker->numberBetween(1, 9),
            'num_garage' => $faker->numberBetween(1, 9),

            'price' => round($faker->numberBetween(100000, 5000000), -3),
            'tax_iptu' => $faker->randomFloat(2, 100, 10000),
            'tax_condominium' => $taxCondominium,
        ];
    }
}
