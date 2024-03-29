<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostQuote implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public $data;

	public function __construct($quote)
	{
		$this->data = [
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

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new Channel('QuotesChannel');
	}
}
