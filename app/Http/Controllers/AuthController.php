<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function login(LoginRequest $request): JsonResponse
	{
		$username = $request->username;
		$field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$token = auth()->setTTL($request->remember ? 60 * 24 : 60)->attempt([$field => $username, 'password' => $request->password]);
		if ($token)
		{
			return response()->json([
				'status' => 'success',
				'token'  => $token,
				'user'   => auth()->user(),
			]);
		}

		return response()->json([
			'message' => 'error',
			'error'   => ['username' => ['invalid_credentials']],
		], 401);
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
		$token = Auth::attempt($user);

		$email_token = Str::random(64);

		DB::table('email_verifications')->insert([
			'user_id' => $user->id,
			'token'   => $email_token,
		]);

		Mail::send('EmailVerification', [
			'url'      => url(env('FRONT_END') . '/verified/' . $email_token),
			'username' => $user->username,
		], function ($message) use ($user) {
			$message->to($user->email);
			$message->subject('Verify your email address');
		});

		return response()->json([
			'status' => 'success',
			'token'  => $token,
			'user'   => Auth::user(),
		]);
	}

	public function me(): JsonResponse
	{
		$user = auth()->user();
		if ($user)
		{
			return response()->json(['user' => $user, 'status' => 'success']);
		}
		return response()->json(['status' => 'error'], 401);
	}

	public function verify($token): JsonResponse
	{
		$email_token = EmailVerification::where('token', $token)->first();
		if ($email_token)
		{
			$user = $email_token->user;
			if ($user->email_verified_at == null)
			{
				$user->markEmailAsVerified();
				$user->save();
				$email_token->delete();
				return response()->json(['status' => 'success', 'user' => $user]);
			}
		}
		return response()->json(['status' => 'error']);
	}
}
