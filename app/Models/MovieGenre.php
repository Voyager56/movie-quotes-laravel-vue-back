<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieGenre extends Model
{
	use HasFactory;

	protected $fillable = [
		'movie_id',
		'genre_id',
	];

	public function movie()
	{
		return $this->belongsTo(Movie::class);
	}

	public function genre()
	{
		return $this->belongsTo(Genre::class);
	}
}
