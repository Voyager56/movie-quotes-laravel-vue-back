<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
	public function edit(EditUserRequest $request): JsonResponse
	{
		$data = $request->all();
		$image = $request->file('file')->store('public/images');
		$image = str_replace('public/', '', $image);

		$user = auth()->user();
		$user->update($data);
		$user->photo = ENV('BACKEND_URL') . 'storage/' . $image;
		$user->save();
		return response()->json('Profile updated', 200);
	}
}
