<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
	protected $fillable = ['user_id', 'quote_id', 'amount'];

	use HasFactory;

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function quote()
	{
		return $this->belongsTo(Quote::class);
	}
}
