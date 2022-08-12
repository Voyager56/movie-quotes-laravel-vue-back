<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Events\SendNotificationEvent;
use App\Http\Resources\CommentResource;
use App\Models\Comments;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
	public function index(): JsonResponse
	{
		$data = CommentResource::collection(Comments::all());
		return response()->json($data, 200);
	}

	public function store(Quote $quote, Request $request): JsonResponse
	{
		$commentAuthor = auth()->user();
		$comment = $quote->comments()->create([
			'body'    => $request->comment,
			'user_id' => $commentAuthor->id,
		]);
		if ($quote->user_id != $commentAuthor->id)
		{
			$notification = Notification::create([
				'to_user_id'   => $quote->user_id,
				'from_user_id' => $commentAuthor->id,
				'type'         => 'comment',
				'read'         => false,
			]);
			SendNotificationEvent::dispatch($notification, $commentAuthor);
		}
		CommentEvent::dispatch($commentAuthor, $comment);
		return response()->json(['success' => true], 200);
	}
}
