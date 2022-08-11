<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MovieTest extends TestCase
{
	protected $user;

	use WithFaker;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->setUpFaker();
	}

	public function test_if_movies_are_returned_from_index_method(): void
	{
		$this->actingAs($this->user)->get('/api/movies')->assertStatus(200);
	}

	public function test_if_correct_movie_is_returned_from_show_method()
	{
		$movie = Movie::inRandomOrder()->first();
		$response = $this->actingAs($movie->user)->get('/api/movies/' . $movie->id);

		$response->assertStatus(
			200,
		);
	}

	public function test_if_correct_data_is_sent_movie_should_be_saved()
	{
		Storage::fake('public');
		$response = $this->actingAs($this->user)->post('/api/movies', [
			'title_ka'                    => $this->faker->sentence,
			'title_en'                    => $this->faker->sentence,
			'release_year'                => $this->faker->year,
			'image'                       => UploadedFile::fake()->image('avatar.jpg'),
			'budget'                      => $this->faker->numberBetween(1, 1000000),
			'description_en'              => $this->faker->sentence,
			'description_ka'              => $this->faker->sentence,
			'director_en'                 => $this->faker->sentence,
			'director_ka'                 => $this->faker->sentence,
			'genres'                      => Genre::first()->name,
		]);
		$response->assertStatus(200);
	}

	public function test_if_correct_data_is_sent_movie_should_be_updated()
	{
		Storage::fake('public');
		$movie = Movie::inRandomOrder()->first();
		$response = $this->actingAs($movie->user)->put('/api/movies/' . $movie->id, [
			'title_ka'                    => $this->faker->sentence,
			'title_en'                    => $this->faker->sentence,
			'release_year'                => $this->faker->year,
			'image'                       => UploadedFile::fake()->image('avatar.jpg'),
			'budget'                      => $this->faker->numberBetween(1, 1000000),
			'description_en'              => $this->faker->sentence,
			'description_ka'              => $this->faker->sentence,
			'director_en'                 => $this->faker->sentence,
			'director_ka'                 => $this->faker->sentence,
			'genres'                      => Genre::first()->name,
		]);
		$response->assertStatus(200);
	}
}
