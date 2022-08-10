<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	public function jwtActingAs($user, $driver = null)
	{
		$token = JWTAuth::fromUser($user);

		$this->withHeader('Authorization', "Bearer {$token}");
		parent::actingAs($user);

		return $this;
	}
}
