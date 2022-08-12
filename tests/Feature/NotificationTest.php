<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationTest extends TestCase
{
	protected $user;

	use WithFaker;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->setUpFaker();
	}

	public function test_if_notifications_are_returned_from_index_method(): void
	{
		$this->actingAs($this->user)->get('/api/notifications')->assertStatus(200);
	}

	public function test_see_if_notification_destroy_method_sets_notification_as_read(): void
	{
		$notification = Notification::inRandomOrder()->first();
		$response = $this->actingAs($this->user)->post('/api/notifications/' . $notification->id);
		$response->assertStatus(200);
		$this->assertEquals(1, $notification->fresh()->read);
	}

	public function test_see_if_destroy_all_method_sets_all_notifications_as_read()
	{
		$response = $this->actingAs($this->user)->post('/api/notifications/all');
		$response->assertStatus(200);
	}
}
