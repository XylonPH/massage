<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'username' => 'testuser'.fake()->unique()->numberBetween(1000, 9999999),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= 'a factory-generated test passphrase 2026',
            'birth_date' => fake()->dateTimeBetween('-60 years', '-19 years')->format('Y-m-d'),
            'status_account' => 'ACT',
            'status_membership' => 'ACT',
            'terms_accepted_at' => now(),
            'terms_accepted_version' => config('legal.terms_version'),
            'privacy_acknowledged_at' => now(),
            'privacy_acknowledged_version' => config('legal.privacy_version'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
            'status_account' => 'PND',
            'status_membership' => 'PEL',
        ]);
    }
}
