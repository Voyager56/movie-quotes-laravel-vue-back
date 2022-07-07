<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function login(LoginRequest $request): JsonResponse
	{
		$username = $request->username;
		$field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$token = Auth::attempt([$field => $username, 'password' => $request->password]);
		$username = User::where($field, $username)->first();
		if ($token)
		{
			return response()->json([
				'status' => 'success',
				'token'  => $token,
				'user'   => Auth::user(),
			]);
		}

		if ($username && !Hash::check($request->password, $username->password))
		{
			return response()->json(['errors' => ['password' => 'wrong password']], 401);
		}
		else
		{
			return response()->json(
				[
					'errors' => [
						'username' => 'wrong username or email',
					],
				],
				401
			);
		}
	}

	public function logout(): JsonResponse
	{
		Auth::logout();
		return response()->json(['status' => 'success']);
	}

	public function register(RegistrationRequest $request): JsonResponse
	{
		$user = User::create([
			'username' => $request->username,
			'email'    => $request->email,
			'password' => Hash::make($request->password),
		]);
		$token = Auth::login($user);
		return response()->json([
			'status' => 'success',
			'token'  => $token,
			'user'   => Auth::user(),
		]);
	}

	public function me(): JsonResponse
	{
		return response()->json(['user' => Auth::user()]);
	}
}
