<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function index(): JsonResponse
	{
		$notifications = Notification::where('to_user_id', auth()->user()->id)->with('from_user')->get();
		return response()->json($notifications, 200);
	}

	public function destroyAll(): JsonResponse
	{
		Notification::where('to_user_id', auth()->user()->id)->update(['read' => 1]);
		return response()->json(['success' => true], 200);
	}

	public function destroy(Notification $notification): JsonResponse
	{
		$notification->update(['read' => 1]);
		return response()->json(['success' => true], 200);
	}
}
