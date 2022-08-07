<?php

namespace App\Http\Controllers;

use App\Models\Genre;

class GenreController extends Controller
{
	public function index()
	{
		return response()->json(['genres' => Genre::all()->pluck('name')]);
	}
}
