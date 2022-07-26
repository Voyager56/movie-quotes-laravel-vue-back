<?php

namespace App\Http\Controllers;

use App\Events\LikeEvent;
use App\Events\PostQuote;
use App\Events\RemoveLikeEvent;
use App\Events\SendNotificationEvent;
use App\Models\Likes;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuoteController extends Controller
{
    public function index(Request $request)
    {

    $searchKeyword = $request->query('search');

    $quotes = null;
    if(strlen($searchKeyword) > 0){
        $quotes = Quote::where('text', 'LIKE', "%{$searchKeyword}%")
            ->orWhere('text', 'LIKE', "%{$searchKeyword}%")
            ->orderBy('created_at', 'desc')->get();

    }else{    
        $quotes = Quote::orderBy('created_at', 'desc')->paginate(5);
    }
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
            'userLikes' => $quote->likes,
        ];
    }
        return response()->json($data);
    }   


    public function addLike($quoteId){

        $alreadyLiked = Likes::where('user_id', auth()->user()->id)->where('quote_id', $quoteId)->first();
        $user = auth()->user();

        if($alreadyLiked){
            $alreadyLiked->delete();
            RemoveLikeEvent::dispatch($alreadyLiked);
            return response()->json(['message' => 'Like removed']);
        }

        $like =  Likes::create([
            'user_id' => $user->id,
            'quote_id' => (int)$quoteId,
        ]);

        $notification = Notification::create([
            'user_id' => $like->quote->user_id,
            "from_id" => $user->id,
            'type' => 'like',
            'read' => false,
        ]);


        SendNotificationEvent::dispatch($notification, $user);
        LikeEvent::dispatch($like);

        return response()->json(['message' => 'Like added']);
    }

    public function store(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'quote_ka' => 'required',
            'quote_en' => 'required',
            "file" => "required",
        ]);


        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $image = $request->file('');
        dd($request->file());
        $imageName = time().'.'.$image->name;
        $image->move(public_path('images'), $imageName);
        $imageUrl = 'http://127.0.0.1:8000/storage/images/'.$imageName;

        $quote = Quote::create([
            'text' => [
                'ka' => $data['quote_ka'],
                'en' => $data['quote_en'],
            ],
            'movie_id' => $data['movie_id'],
            'thumbnail' => $imageUrl,
            'user_id' => auth()->user()->id,
        ]);

        PostQuote::dispatch($quote);

        return response()->json(['message' => 'Quote added']);
    }
}
