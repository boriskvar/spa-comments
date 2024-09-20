<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class CommentFactory extends Factory
{
    protected $model = \App\Models\Comment::class;

    public function definition()
    {
        return [
            'user_name' => $this->faker->name,
            'avatar' => 'storage/app/public/images/avatars/' . $this->faker->uuid . '.png',
            'email' => $this->faker->unique()->safeEmail,
            'home_page' => $this->faker->url,
            'captcha' => 'example_captcha',
            'text' => $this->faker->paragraph,
            'rating' => $this->faker->numberBetween(1, 5),
            'quote' => $this->faker->sentence,
            'file_path' => 'storage/app/public/files/' . $this->faker->uuid . '.txt',
        ];
    }
}
