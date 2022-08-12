<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return [
			'id'           => $this->id,
			'quote'        => $this->getTranslations('text'),
			'thumbnail'    => $this->thumbnail,
			'commentCount' => $this->comments->count(),
			'user'         => $this->user,
			'movie_name'   => $this->movie->getTranslations('title'),
			'release_year' => $this->movie->release_year,
			'director'     => $this->movie->getTranslations('director'),
			'likes'        => $this->likes->count(),
			'userLikes'    => $this->likes,
		];
	}
}
