<?php

namespace App\Http\Controllers;

use App\Models\Likes;
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
            "id" => $quote->id,
            'quote' => $quote->text,
            'thumbnail' => $quote->thumbnail,
            "commentCount" => $quote->comments->count(),
            'user' => $quote->user,
            'movie_name' => $quote->movie->title,
            'release_year' => $quote->movie->release_year,
            "director" => $quote->movie->director,
            "likes" => $quote->likes->count(),
            'userLikes' => $quote->likes->where('user_id', auth()->user()->id),
        ];
    }
        return response()->json($data);
    }   


    public function addLike($quoteId){
        Likes::create([
            'user_id' => auth()->user()->id,
            'quote_id' => $quoteId,
        ]);
    }
}
