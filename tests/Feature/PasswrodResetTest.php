<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PasswrodResetTest extends TestCase
{
	protected $user;

	use WithFaker;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->setUpFaker();
	}

	public function test_see_if_forgot_password_route_is_sending_email()
	{
		$response = $this->actingAs($this->user)->post('/api/forgot-password', [
			'email' => $this->user->email,
		]);
		$response->assertStatus(200);
	}

	public function test_see_if_password_reset_route_is_changing_password()
	{
		$this->actingAs($this->user)->post('/api/forgot-password', [
			'email' => $this->user->email,
		]);

		$token = DB::table('password_resets')->where('email', $this->user->email)->first()->token;

		$response = $this->actingAs($this->user)->post('/api/reset-password/' . $token, [
			'password'              => '123456',
			'password_confirmation' => '123456',
		]);
		$response->assertStatus(200);
	}

	public function test_if_wrong_token_is_sent_error_is_returned()
	{
		$response = $this->actingAs($this->user)->post('/api/reset-password/' . 'wrong-token', [
			'password'              => '123456',
			'password_confirmation' => '123456',
		]);
		$response->assertStatus(401);
	}
}
