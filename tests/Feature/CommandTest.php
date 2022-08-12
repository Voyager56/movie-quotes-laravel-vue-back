<?php

namespace Tests\Feature;

use Tests\TestCase;

class CommandTest extends TestCase
{
	public function test_see_if_command_creates_genres_tables()
	{
		$this->artisan('genre:populate')->assertExitCode(0);
	}
}
