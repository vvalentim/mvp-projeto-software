<?php

namespace Database\Seeders;

use App\Enums\LeadStatus;
use App\Models\FollowUp;
use App\Models\Lead;
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
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => '123abc',
        ]);

        $brokerA = User::factory()->create([
            'name' => 'Broker A',
            'email' => 'a@broker.com',
            'password' => '123abc',
        ]);

        $brokerB = User::factory()->create([
            'name' => 'Broker B',
            'email' => 'b@broker.com',
            'password' => '123abc',
        ]);

        $leads = Lead::factory(20)->create();

        $followUps = FollowUp::factory(20)
            ->recycle($admin, $brokerA, $brokerB)
            ->recycle($leads)
            ->create();

        foreach ($followUps as $followUp) {
            $lead = $followUp->lead;
            $lead->status = LeadStatus::Assigned;
            $lead->save();
        }
    }
}
