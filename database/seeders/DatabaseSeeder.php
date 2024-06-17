<?php

namespace Database\Seeders;

use App\Enums\MaritalStatus;
use App\Enums\UserRoles;
use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\Person;
use App\Models\RealEstate;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => '123abc',
            'role' => UserRoles::Admin
        ]);

        $brokerA = User::factory()->create([
            'name' => 'operador',
            'email' => 'operador@mail.com',
            'password' => '123abc',
            'role' => UserRoles::Operator
        ]);

        $brokerB = User::factory()->create([
            'name' => 'corretor',
            'email' => 'corretor@mail.com',
            'password' => '123abc',
            'role' => UserRoles::Broker
        ]);


        $estates = RealEstate::factory(20)->create();
        $people = Person::factory(30)->create();
        $customers = [];

        foreach ($people as $person) {
            $customers[] = Customer::factory()
                ->recycle($person)
                ->create();
        }

        // Define required fields for customers of type 'Pessoa Física' 
        foreach ($customers as $customer) {
            if ($customer->person->type === 'F') {
                $faker = fake('pt_BR');

                $customer->filiation_mother = "{$faker->firstName('female')} {$faker->lastName('female')}";
                $customer->filiation_father = "{$faker->firstName('male')} {$faker->lastName('male')}";
                $customer->marital_status = $faker->randomElement(MaritalStatus::values());
                $customer->profession = $faker->randomElement([
                    'Engenheiro Civil',
                    'Professor(a)',
                    'Analista de Sistemas',
                    'Advogado(a)',
                    'Médico(a)',
                    'Autônomo',
                ]);

                $customer->save();
            }
        }

        // Associate at least one customer as an owner of an estate
        foreach ($estates as $estate) {
            $owners = Customer::query()->inRandomOrder()->limit(3)->get();
            $owners = fake()->randomElements($owners, rand(1, 3));

            foreach ($owners as $owner) {
                $estate->owners()->attach($owner->id);
            }
        }

        $leads = Lead::factory(20)
            ->recycle($estates)
            ->recycle($admin, $brokerA, $brokerB)
            ->assigned()
            ->create();

        // Replicate assigned leads data into follow-ups
        foreach ($leads as $lead) {
            FollowUp::factory()
                ->recycle($admin, $brokerA, $brokerB)
                ->create([
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'subject' => $lead->subject,
                    'message' => $lead->message,
                    'real_estate_id' => $lead->real_estate_id,
                    'user_id' => $lead->user_id,
                ]);
        }

        // Generate more unverified leads
        Lead::factory(50)
            ->recycle($estates)
            ->create();
    }
}
