<?php

namespace App\Http\Controllers;

use App\Events\NotificationUpdate;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index(){
        $notifications = Notification::where('user_id', auth()->user()->id)->get();
        $data = [];
        foreach($notifications as $notification){
            $data[] = [
                "notification" => $notification,
                "comingFrom" => $notification->from,
            ];
        }
        return response()->json($data);
    }

    public function destroyAll()
    {
        Notification::where('user_id', auth()->user()->id)->update(['read' => 1]);
        return response()->json(['success' => true]);
    }

    public function destroy($id){
        Notification::where('id', $id)->update(['read' => 1]);
        return response()->json(['success' => true]);
    }
}
