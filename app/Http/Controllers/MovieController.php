<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\MovieGenre;
use Illuminate\Http\Request;

class MovieController extends Controller
{
	public function index()
	{
		$movies = auth()->user()->movies()->with('quotes')->get();
		return response()->json($movies);
	}

	public function store(MovieRequest $request)
	{
		$genres = explode(',', $request->genres);
		$imageName = $request->file('image')->store('public/images');
		$imageUrl = 'http://127.0.0.1:8000/storage/' . explode('public/', $imageName)[1];

		$data = $request->all();

		$movie = Movie::create([
			'title'                    => [
				'ka' => $data['title_ka'],
				'en' => $data['title_en'],
			],
			'release_year'             => $request->release_year,
			'thumbnail'                => $imageUrl,
			'budget'                   => $request->budget,
			'description'              => ['en' => $data['description_en'], 'ka' => $data['description_ka']],
			'director'                 => ['en' => $data['director_en'], 'ka' => $data['director_ka']],
			'user_id'                  => auth()->user()->id,
		]);

		foreach ($genres as $genre)
		{
			MovieGenre::create([
				'movie_id' => $movie->id,
				'genre_id' => Genre::where('name', $genre)->first()->id,
			]);
		}

		return response()->json('Movie created');
	}

	public function show($id)
	{
		$movie = Movie::with('genres')->find($id);
		$quotes = $movie->quotes()->with('comments', 'likes')->get();
		return response()->json([
			'movie'  => $movie,
			'quotes' => $quotes,
		]);
	}

	public function search(Request $request)
	{
		$searchKeyword = $request->query('search');
		$movies = null;
		if (strlen($searchKeyword) > 0)
		{
			$movies = Movie::where('title->en', 'LIKE', "%{$searchKeyword}%")
			->orWhere('title->ka', 'LIKE', "%{$searchKeyword}%")
			->orderBy('created_at', 'desc')->get();
		}
		else
		{
			$movies = auth()->user()->movies()->orderBy('created_at', 'desc')->paginate(5);
		}
		$data = [];
		foreach ($movies as $movie)
		{
			$data[] = [
				'id'           => $quote->id,
				'quote'        => $quote->text,
				'thumbnail'    => $quote->thumbnail,
				'commentCount' => $quote->comments->count(),
				'user'         => $quote->user,
				'movie_name'   => $quote->movie->title,
				'release_year' => $quote->movie->release_year,
				'director'     => $quote->movie->director,
				'likes'        => $quote->likes->count(),
				'userLikes'    => $quote->likes,
			];
		}
		return response()->json($data);
	}
}
