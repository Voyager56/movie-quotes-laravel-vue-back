<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Events\SendNotificationEvent;
use App\Models\Comments;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index(){
        $comments = Comments::all();
        $data = [];
        foreach($comments as $comment){
            $data[] = [
                "quoteId" => $comment->quote->id,
                'comment' => $comment->body,
                'authorPhoto' => $comment->user->photo,
                'authorUsername' => $comment->user->username,
            ];  
        }
        return response()->json($data);
    }

    public function store($quoteId, Request $request,){
        $quote = Quote::find($quoteId);
        $commentAuthor = User::find($request->userId);
        if($comment = $quote->comments()->create([
            'body' => $request->comment,
            'user_id' => $request->userId,
        ]))
        {
            $notification = Notification::create([
                'user_id' => $quote->user_id,
                "from_id" => $commentAuthor->id,
                'type' => 'comment',
                'read' => false,
            ]);
            CommentEvent::dispatch($commentAuthor, $comment);
            SendNotificationEvent::dispatch($notification, $commentAuthor );
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
