<?php

namespace App\Http\Controllers;

use App\Events\LikeEvent;
use App\Events\PostQuote;
use App\Events\RemoveLikeEvent;
use App\Events\SendNotificationEvent;
use App\Http\Requests\QuoteRequest;
use App\Models\Likes;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
	public function index(): JsonResponse
	{
		$quotes = Quote::orderBy('created_at', 'desc')->paginate(5);
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

	public function show($id): JsonResponse
	{
		$quote = Quote::with('likes')->findorFail($id);
		$comments = $quote->comments()->with('user')->get();
		return response()->json([
			'quote'    => $quote,
			'comments' => $comments,
		], 200);
	}

	public function store(QuoteRequest $request): JsonResponse
	{
		$data = $request->all();

		$imageName = $request->file('image')->store('public/images');
		$imageUrl = 'http://127.0.0.1:8000/storage/' . explode('public/', $imageName)[1];

		$quote = Quote::create([
			'text' => [
				'ka' => $data['quote_ka'],
				'en' => $data['quote_en'],
			],
			'movie_id'  => $data['movie'],
			'thumbnail' => $imageUrl,
			'user_id'   => auth()->user()->id,
		]);

		PostQuote::dispatch($quote);

		return response()->json(['message' => 'Quote added'], 200);
	}

	public function destroy($id): JsonResponse
	{
		$quote = Quote::firstWhere('user_id', auth()->user()->id)->find($id);
		$quote->delete();
		return response()->json(['message' => 'Quote deleted'], 200);
	}

	public function update(Quote $quote, QuoteRequest $request): JsonResponse
	{

		$imageName = $request->file('image')->store('public/images');
		$imageUrl = 'http://127.0.0.1:8000/storage/' . explode('public/', $imageName)[1];

		$quote->update([
			'text' => [
				'ka' => $request->quote_ka,
				'en' => $request->quote_en,
			],
			'thumbnail' => $imageUrl,
		]);
		return response()->json(['message' => 'Quote updated'], 200);
	}

	public function search(Request $request): JsonResponse
	{
		$searchKeyword = $request->search;
		$quotes = null;
		if ($searchKeyword == '')
		{
			$quotes = Quote::orderBy('created_at', 'desc')->paginate(5);
		}
		else
		{
			$quotes = Quote::where('text', 'LIKE', '%' . $searchKeyword . '%')->get();
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

	public function addLike($quoteId): JsonResponse
	{
		$alreadyLiked = Likes::where('user_id', auth()->user()->id)->firstWhere('quote_id', $quoteId);
		$user = auth()->user();

		if ($alreadyLiked)
		{
			$alreadyLiked->delete();
			RemoveLikeEvent::dispatch($alreadyLiked);
			return response()->json(['message' => 'Like removed'], 200);
		}

		$like = Likes::create([
			'user_id'  => $user->id,
			'quote_id' => (int)$quoteId,
		]);

		if ($user->id !== $like->quote->user_id)
		{
			$notification = Notification::create([
				'to_user_id'   => $like->quote->user_id,
				'from_user_id' => $user->id,
				'type'         => 'like',
				'read'         => false,
			]);
			SendNotificationEvent::dispatch($notification, $user);
		}

		LikeEvent::dispatch($like);

		return response()->json(['message' => 'Like added'], 200);
	}
}
