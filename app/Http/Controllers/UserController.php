<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function edit(EditUserRequest $request): JsonResponse
	{
		$data = $request->all();
		$image = $request->file('file')->store('public/images');
		$image = str_replace('public/', '', $image);

		$user = auth()->user();
		$user->update($data);
		$user->photo = 'http://127.0.0.1:8000/storage/' . $image;
		$user->save();
		return response()->json(['message' => 'Profile updated'], 200);
	}
}
