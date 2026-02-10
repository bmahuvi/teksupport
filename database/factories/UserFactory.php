<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = Company::inRandomOrder()->where('is_main', false)->first();
        $user = User::whereHas('company', function ($query) {
            $query->where('is_main', 1);
        })->inRandomOrder()->first();

        $gender = $this->faker->randomElement(['Male', 'Female']);

        return [
            'name' => join(' ', [$this->faker->firstName($gender), $this->faker->lastName()]),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 1,
            'remember_token' => Str::random(10),
            'phone' => '0762' . fake()->randomNumber(6),
            'company_id' => $company->id,
            'gender' => $gender,
            'status' => $this->faker->randomElement([0, 1, 2]),
            'ulid' => strtolower(Str::ulid()),
            'created_by' => $user->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }


    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            Role::findOrCreate('admin', 'web');
            $user->assignRole('admin');
        });
    }

    public function manager(): static
    {
        return $this->afterCreating(function (User $user) {
            Role::findOrCreate('manager', 'web');
            $user->assignRole('manager');
        });
    }
}
