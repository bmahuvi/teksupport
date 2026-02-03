<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\District;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $district = District::inRandomOrder()->first();

        $user = User::whereHas('company', function ($query) {
            $query->where('is_main', 1);
        })->inRandomOrder()->first();

        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->companyEmail,
            'phone' => '0762' . fake()->randomNumber(6),
            'district_id' => $district->id,
            'region_id' => $district->region_id,
            'created_by' => $user->id,
            'ulid' => strtolower(Str::ulid()),
            'is_main' => false,
            'is_active' => true,
        ];
    }
}
