<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
	use HasFactory;

	use HasTranslations;

	public $translatable = ['title', 'description', 'director'];

	protected $fillable = [
		'title',
		'thumbnail',
		'release_year',
		'description',
		'director',
		'user_id',
		'budget',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function quotes()
	{
		return $this->hasMany(Quote::class);
	}

	public function genres()
	{
		return $this->belongsToMany(Genre::class, 'movie_genres');
	}
}
