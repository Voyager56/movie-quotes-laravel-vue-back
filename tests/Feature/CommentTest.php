<?php

namespace Tests\Feature;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
	protected $user;

	use WithFaker;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->setUpFaker();
	}

	public function test_see_if_comment_store_method_works()
	{
		$quoteId = Quote::inRandomOrder()->first()->id;
		$this->actingAs($this->user)->post('api/comment/' . $quoteId, [
			'comment' => $this->faker->sentence,
		])->assertStatus(200);
	}

	public function test_if_comments_are_returned_from_index_method(): void
	{
		$this->actingAs($this->user)->get('/api/comments')->assertStatus(200);
	}
}
