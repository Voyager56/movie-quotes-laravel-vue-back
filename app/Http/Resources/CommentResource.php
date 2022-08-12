<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
			'quoteId'        => $this->quote->id,
			'comment'        => $this->body,
			'authorPhoto'    => $this->user->photo,
			'authorUsername' => $this->user->username,
		];
	}
}
