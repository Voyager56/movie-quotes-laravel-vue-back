<?php

namespace Tests\Feature;

use App\Models\Quote;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class QuoteTest extends TestCase
{
	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	protected $user;

	use WithFaker;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->setUpFaker();
	}

	public function test_if_quotes_are_returned_from_index_method(): void
	{
		$this->actingAs($this->user)->get('/api/quotes')->assertStatus(200);
	}

	public function test_if_correct_quote_is_returned_from_show_method()
	{
		$id = Quote::inRandomOrder()->first()->id;
		$response = $this->actingAs($this->user)->get('/api/quotes/' . $id);

		$this->assertSame(
			$response->original['quote']->id,
			$id
		);
	}

	public function test_if_correct_data_is_sent_quote_should_be_saved()
	{
		Storage::fake('public');
		$response = $this->actingAs($this->user)->post('/api/quotes', [
			'quote_en'    => $this->faker->sentence,
			'quote_ka'    => $this->faker->sentence,
			'image'       => UploadedFile::fake()->image('avatar.jpg'),
			'movie'       => 1,
			'user_id'     => $this->user->id,
		]);

		$response->assertJson([
			'message' => 'Quote added',
		]);
	}

	public function test_see_if_quote_delete_method_works()
	{
		$quote = Quote::inRandomOrder()->first();
		$user = $quote->user;
		$this->actingAs($user)->delete('/api/quotes/' . $quote->id)->assertJson([
			'message' => 'Quote deleted',
		]);
	}

	public function test_check_if_quote_upadte_method_works()
	{
		$id = Quote::inRandomOrder()->first()->id;
		$response = $this->actingAs($this->user)->put('/api/quotes/' . $id, [
			'quote_en'    => $this->faker->sentence,
			'quote_ka'    => $this->faker->sentence,
			'image'       => UploadedFile::fake()->image('avatar.jpg'),
		]);
		$response->assertJson([
			'message' => 'Quote updated',
		]);
	}

	public function test_search_functionality()
	{
		$text = Quote::inRandomOrder()->first()->text;
		$this->actingAs($this->user)->get('/api/quotes/search?search=' . $text, [
			'search' => $text,
		])->assertStatus(200);
	}

	public function test_if_no_search_query_string_then_all_quotes_are_returned()
	{
		$this->actingAs($this->user)->get('/api/quotes/search?search=')->assertStatus(200);
	}

	public function test_see_if_liking_quote_works()
	{
		$quote = Quote::inRandomOrder()->first();
		$this->actingAs($this->user)->post('/api/likes/' . $quote->id)->assertStatus(200);
	}

	public function test_liking_quote_second_time_should_remove_like()
	{
		$quote = Quote::inRandomOrder()->first();
		$this->actingAs($this->user)->post('/api/likes/' . $quote->id)->assertStatus(200);
		$this->actingAs($this->user)->post('/api/likes/' . $quote->id)->assertJson([
			'message' => 'Like removed',
		]);
	}
}
