<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_name' => $this->faker->name(),
            'avatar' => 'storage/app/public/images/avatars/' . $this->faker->image('storage/app/public/images/avatars', 100, 100, 'people', false),
            'email' => $this->faker->unique()->safeEmail(),
            'home_page' => $this->faker->url(),
            'captcha' => $this->faker->word(),
            'text' => $this->faker->paragraph(),
            'rating' => $this->faker->numberBetween(1, 5),
            'quote' => $this->faker->sentence(),
            'fileInput' => 'storage/app/public/files/' . $this->faker->file('storage/app/temp', 'storage/app/public/files', false),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
