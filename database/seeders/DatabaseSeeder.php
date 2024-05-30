<?php

namespace Database\Seeders;

use App\Enums\LeadStatus;
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
        $admin = User::factory()
            ->recycle(Person::factory()->create())
            ->create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => '123abc',
            ]);

        $brokerA = User::factory()
            ->recycle(Person::factory()->create())
            ->create([
                'name' => 'broker.a',
                'email' => 'a@broker.com',
                'password' => '123abc',
            ]);

        $brokerB = User::factory()
            ->recycle(Person::factory()->create())
            ->create([
                'name' => 'broker.b',
                'email' => 'b@broker.com',
                'password' => '123abc',
            ]);


        $estates = RealEstate::factory(20)->create();
        $people = Person::factory(30)->create();
        $customers = [];

        foreach ($people as $person) {
            $customers[] = Customer::factory()
                ->recycle($person)
                ->create();
        }

        // Associate at least one customer as an owner of an estate
        foreach ($estates as $estate) {
            $owners = Customer::query()->inRandomOrder()->limit(3)->get();
            $owners = fake()->randomElements($owners, rand(1, 3));

            foreach ($owners as $owner) {
                $estate->owners()->attach($owner->id);
            }
        }

        $leads = Lead::factory(50)
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
