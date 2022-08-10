<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
	public function index()
	{
		$notifications = Notification::where('to_user_id', auth()->user()->id)->get();
		$data = [];
		foreach ($notifications as $notification)
		{
			$data[] = [
				'notification' => $notification,
				'comingFrom'   => $notification->from,
			];
		}
		return response()->json($data, 200);
	}

	public function destroyAll()
	{
		Notification::where('to_user_id', auth()->user()->id)->update(['read' => 1]);
		return response()->json(['success' => true], 200);
	}

	public function destroy(Notification $notification)
	{
		$notification->update(['read' => 1]);
		return response()->json(['success' => true], 200);
	}
}
