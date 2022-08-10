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
use Illuminate\Http\Request;

class QuoteController extends Controller
{
	public function index()
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

	public function show($id)
	{
		$quote = Quote::with('likes')->findorFail($id);
		$comments = $quote->comments()->with('user')->get();
		return response()->json([
			'quote'    => $quote,
			'comments' => $comments,
		]);
	}

	public function store(QuoteRequest $request)
	{
		$data = $request->all();

		$imageName = $request->file('file')->store('public/images');
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

		return response()->json(['message' => 'Quote added']);
	}

	public function destroy($id)
	{
		$quote = Quote::find($id);
		$quote->delete();
		return response()->json(['message' => 'Quote deleted']);
	}

	public function update($id, QuoteRequest $request)
	{
		$quote = Quote::find($id);

		$imageName = $request->file('image')->store('public/images');
		$imageUrl = 'http://127.0.0.1:8000/storage/' . explode('public/', $imageName)[1];

		$quote->update([
			'text' => [
				'ka' => $request->quote_ka,
				'en' => $request->quote_en,
			],
			'thumbnail' => $imageUrl,
		]);
		return response()->json(['message' => 'Quote updated']);
	}

	public function search(Request $request)
	{
		$searchKeyword = $request->search;
		$quotes = Quote::where('text', 'LIKE', '%' . $searchKeyword . '%')->get();
		if ($searchKeyword == '')
		{
			$quotes = Quote::orderBy('created_at', 'desc')->paginate(5);
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
		return response()->json($data);
	}

	public function addLike($quoteId)
	{
		$alreadyLiked = Likes::where('user_id', auth()->user()->id)->where('quote_id', $quoteId)->first();
		$user = auth()->user();

		if ($alreadyLiked)
		{
			$alreadyLiked->delete();
			RemoveLikeEvent::dispatch($alreadyLiked);
			return response()->json(['message' => 'Like removed']);
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

		return response()->json(['message' => 'Like added']);
	}
}
