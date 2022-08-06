<?php

namespace App\Console\Commands;

use App\Models\Genre;
use Illuminate\Console\Command;

class PopulateGenre extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'genre:populate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$genres = [
			'Action',
			'Adventure',
			'Animation',
			'Biography',
			'Comedy',
			'Crime',
			'Documentary',
			'Drama',
			'Family',
			'Fantasy',
			'Film-Noir',
			'History',
			'Horror',
			'Music',
			'Musical',
			'Mystery',
			'Romance',
			'Sci-Fi',
			'Sport',
			'Thriller',
			'War',
			'Western',
		];
		foreach ($genres as $genre)
		{
			Genre::create([
				'name' => $genre,
			]);
		}
	}
}
