<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
	public function loginUser(LoginRequest $request): JsonResponse
	{
		$username = $request->username;
//		$field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
//		$token = Auth::attempt([$field => $username, 'password' => $request->password]);
		if (!Auth::attempt(['username' => $username, 'password' => $request->password]))
		{
			return response()->json('sfsdfsd', 401);
//			return response()->json(['token' => $token], 401);
		}
		return response()->json([
			'status' => 'success',
			'token'  => $token,
			'user'   => Auth::user(),
		]);
	}

	public function logoutUser(): JsonResponse
	{
		Auth::logout();
		return response()->json(['status' => 'success']);
	}

	public function registerUser(RegistrationRequest $request): JsonResponse
	{
		+$user = User::create([
			'username' => $request->username,
			'email'    => $request->email,
			'password' => $request->password,
		]);
		$token = Auth::login($user);
		return response()->json([
			'status' => 'success',
			'token'  => $token,
			'user'   => Auth::user(),
		]);
	}
}
