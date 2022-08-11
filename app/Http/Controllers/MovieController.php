<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\MovieGenre;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
	public function index(): JsonResponse
	{
		$movies = auth()->user()->movies()->with('quotes')->get();
		return response()->json($movies, 200);
	}

	public function store(MovieRequest $request): JsonResponse
	{
		$genres = explode(',', $request->genres);
		$imageName = $request->file('image')->store('public/images');
		$imageUrl = ENV('BACKEND_URL') . '/storage/' . explode('public/', $imageName)[1];

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
				'genre_id' => Genre::firstWhere('name', $genre)->id,
			]);
		}

		return response()->json('Movie created', 200);
	}

	public function update(Movie $movie, MovieRequest $request): JsonResponse
	{
		$imageName = $request->file('image')->store('public/images');
		$imageUrl = ENV('BACKEND_URL') . 'storage/' . explode('public/', $imageName)[1];
		$movie->update([
			'title'                    => [
				'ka' => $request->title_ka,
				'en' => $request->title_en,
			],
			'release_year'             => $request->release_year,
			'thumbnail'                => $imageUrl,
			'budget'                   => $request->budget,
			'description'              => ['en' => $request->description_en, 'ka' => $request->description_ka],
			'director'                 => ['en' => $request->director_en, 'ka' => $request->director_ka],
		]);
		return response()->json('Movie updated', 200);
	}

	public function show(int $id): JsonResponse
	{
		$movie = auth()->user()->movies()->with('genres', 'quotes.comments', 'quotes.likes')->find($id);
		return response()->json([
			'movie'  => $movie,
		], 200);
	}

	public function search(Request $request): JsonResponse
	{
		$searchKeyword = $request->search;
		$quotes = null;
		if ($searchKeyword == '')
		{
			$quotes = Quote::orderBy('created_at', 'desc')->get();
		}
		else
		{
			$quotes = Movie::firstWhere('title', 'LIKE', '%' . $searchKeyword . '%')->quotes()->get();
		}
		$data = [];
		foreach ($quotes as $quote)
		{
			$data[] = [
				'id'           => $quote->id,
				'quote'        => $quote->getTranslations('text'),
				'thumbnail'    => $quote->thumbnail,
				'commentCount' => $quote->comments->count(),
				'user'         => $quote->user,
				'movie_name'   => $quote->movie->getTranslations('title'),
				'release_year' => $quote->movie->release_year,
				'director'     => $quote->movie->getTranslations('director'),
				'likes'        => $quote->likes->count(),
				'userLikes'    => $quote->likes,
			];
		}
		return response()->json($data, 200);
	}

	public function movieSearch(Request $request): JsonResponse
	{
		$searchKeyword = $request->search;
		$movies = null;
		if ($searchKeyword == '')
		{
			$movies = auth()->user()->movies()->with('quotes')->get();
		}
		else
		{
			$movies = auth()->user()->movies()->where('title', 'LIKE', '%' . $searchKeyword . '%')->with('quotes')->get();
		}

		return response()->json($movies, 200);
	}

	public function destroy(int $id): JsonResponse
	{
		$movie = Movie::firstWhere('user_id', auth()->user()->id)->firstWhere('id', $id);
		$movie->delete();
		return response()->json('Movie deleted', 200);
	}
}
