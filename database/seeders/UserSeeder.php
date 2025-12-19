<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Bryson Mahuvi',
            'email'=>'bmahuvi@gmail.com',
            'phone' => '0762691069',
            'email_verified_at' => now(),
            'gender' => 'Male',
            'status' => 'Active',
            'password' => '1',
            'ulid' => Str::ulid()
        ]);
    }
}
