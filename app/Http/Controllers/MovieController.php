<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    
    public function index(){
        $movies = auth()->user()->movies()->get();
        return response()->json($movies);
    }

    public function search(Request $request){
        $searchKeyword = $request->query('search');
        $movies = null;
        if(strlen($searchKeyword) > 0){
            $movies = Movie::where('title->en', 'LIKE', "%{$searchKeyword}%")
            ->orWhere('title->ka', 'LIKE', "%{$searchKeyword}%")
            ->orderBy('created_at', 'desc')->get();

        }else{    
            $movies = auth()->user()->movies()->orderBy('created_at', 'desc')->paginate(5);
        }
        $data = [];
        foreach($movies as $movie) {
            $data[] = [
                "id" => $movie->$quote->id,
                'quote' => $quote->text,
                'thumbnail' => $quote->thumbnail,
                "commentCount" => $quote->comments->count(),
                'user' => $quote->user,
                'movie_name' => $quote->movie->title,
                'release_year' => $quote->movie->release_year,
                "director" => $quote->movie->director,
                "likes" => $quote->likes->count(),
                'userLikes' => $quote->likes,
            ];
        }
        return response()->json($data);
    }
}
