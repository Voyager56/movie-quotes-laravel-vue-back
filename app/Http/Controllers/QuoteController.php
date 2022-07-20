<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
	$quotes = Quote::orderBy('created_at', 'desc')->paginate(5);
    $data = [];
    foreach($quotes as $quote) {
        $data[] = [
            'quote' => $quote->text,
            'thumbnail' => $quote->thumbnail,
            'user' => $quote->user,
            'movie_name' => $quote->movie->title,
            'release_year' => $quote->movie->release_year,
            "director" => $quote->movie->director,
        ];
    }
        return response()->json($data);
    }   
}
