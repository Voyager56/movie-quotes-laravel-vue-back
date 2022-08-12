<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		Artisan::call('genre:populate');
		Quote::factory(10)->create();
		$user = User::create([
			'username'          => 'admin',
			'email'             => 'admin@admin.com',
			'email_verified_at' => now(),
			'password'          => bcrypt('admin'),
		]);
		Movie::factory()->create([
			'user_id' => $user->id,
		]);
		Quote::factory()->create([
			'user_id' => $user->id,
		]);
	}
}
