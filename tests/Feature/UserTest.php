<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class UserTest extends TestCase
{
	protected $user;

	use WithFaker;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->setUpFaker();
		Storage::fake('public');
	}

	public function test_see_if_editing_profile_works()
	{
		$this->actingAs($this->user)->post('api/edit-profile', [
			'username'              => $this->faker->userName(),
			'email'                 => $this->faker->email(),
			'password'              => 'password',
			'password_confirmation' => 'password',
			'file'                  => UploadedFile::fake()->image('avatar.jpg'),
		])->assertStatus(200);
	}
}
