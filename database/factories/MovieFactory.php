<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */



class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        return [
            'title' => [
                "en" => $this->faker->sentence,
                "ka" => \Faker\Factory::create('ka_GE')->realText(10),
            ],
            "thumbnail" => $this->faker->imageUrl,
            'release_year' => $this->faker->year,
            "director" => $this->faker->name,
            "description" => [
                "en" => $this->faker->paragraph,
                "ka" => \Faker\Factory::create('ka_GE')->realText(30),
            ],
            "user_id" => User::factory(),
        ];
    }
}
