<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreTest extends TestCase
{
	protected $user;

	use WithFaker;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->setUpFaker();
	}

	public function test_index_method_of_genres()
	{
		$this->actingAs($this->user)->get('api/genres')->assertStatus(200);
	}
}
