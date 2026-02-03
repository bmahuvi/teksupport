<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating Companies ...');
        Company::factory()->count(10)->create();

        $this->command->info('Creating users with admin role ...');
        User::factory()->count(20)->admin()->create();

        $this->command->info('Creating users with manager role ...');
        User::factory()->count(20)->manager()->create();
    }
}
