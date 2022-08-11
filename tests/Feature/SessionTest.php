<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

class SessionTest extends TestCase
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

	public function test_if_wrong_username_is_entered_then_error_is_returned()
	{
		$response = $this->post('/api/login', [
			'username' => Str::random(64),
			'password' => 'password',
		]);

		$response->assertJson([
			'message'=> 'error',
			'error'  => [
				'username' => [
					'invalid_credentials',
				],
			],
		]);

		$response->assertStatus(401);
	}

	public function test_if_correct_username_is_entered_then_token_is_returned()
	{
		$response = $this->post('/api/login', [
			'username' => $this->user->username,
			'password' => 'password',
		]);

		$response->assertJson([
			'status' => 'success',
		]);

		$response->assertStatus(200);
	}

	public function test_when_logout_route_is_hit_user_is_logged_out()
	{
		$this->post('/api/login', [
			'username' => $this->user->username,
			'password' => 'password',
		]);
		$response = $this->post('/api/logout');

		$response->assertStatus(200);
	}

	public function test_if_wrong_data_is_sent_while_registering_errors_are_returned()
	{
		$response = $this->post('/api/register', [
			'username'              => Str::random(64),
			'email'                 => Str::random(64),
			'password'              => 'password',
			'password_confirmation' => '123',
		]);
		$response->assertSessionHasErrors();
		$response->assertStatus(302);
	}

	public function test_if_correct_data_is_sent_while_registering_then_user_is_created()
	{
		$response = $this->post('/api/register', [
			'username'              => Str::random(64),
			'email'                 => $this->faker->email,
			'password'              => 'password',
			'password_confirmation' => 'password',
		]);

		$response->assertStatus(200);
	}

	public function test_if_me_route_is_working_for_authenticated_user()
	{
		$this->post('/api/login', [
			'username' => $this->user->username,
			'password' => 'password',
		]);
		$response = $this->post('/api/me');
		$response->assertJson([
			'status' => 'success',
		]);
		$response->assertStatus(200);
	}

	public function test_if_me_is_hit_by_unauthenticated_user_then_error_is_returned()
	{
		$response = $this->post('/api/me');
		$response->assertJson([
			'status' => 'error',
		]);
		$response->assertStatus(401);
	}

	public function test_if_wrong_token_is_sent_to_verify_then_error_is_returned()
	{
		$response = $this->get(
			'/api/email-verification/' . Str::random(64)
		);
		$response->assertJson([
			'status' => 'error',
		]);
		$response->assertStatus(401);
	}

	public function test_if_email_is_verifiable()
	{
		$user = $this->post('/api/register', [
			'username'              => Str::random(64),
			'email'                 => $this->faker->email,
			'password'              => 'password',
			'password_confirmation' => 'password',
		]);

		$userId = $user->original['user']->id;

		$token = DB::table('email_verifications')->where('user_id', $userId)->first()->token;


		$response = $this->get('/api/email-verification/' . $token);

		$response->assertJson([
			'status' => 'success',
		]);
		$response->assertStatus(200);
	}
}
