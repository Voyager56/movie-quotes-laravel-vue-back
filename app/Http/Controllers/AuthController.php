<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function login(LoginRequest $request): JsonResponse
	{
		$field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$token = auth()->setTTL($request->remember ? 60 * 24 : 60)->attempt([$field => $request->username, 'password' => $request->password]);
		if ($token)
		{
			return response()->json([
				'status' => 'success',
				'token'  => $token,
				'user'   => auth()->user(),
			], 200);
		}

		return response()->json([
			'message' => 'error',
			'error'   => ['username' => ['invalid_credentials']],
		], 401);
	}

	public function logout(): JsonResponse
	{
		auth()->logout();
		return response()->json(['status' => 'success'], 200);
	}

	public function register(RegistrationRequest $request): JsonResponse
	{
		DB::beginTransaction();
		$user = User::create([
			'username' => $request->username,
			'email'    => $request->email,
			'password' => Hash::make($request->password),
		]);
		$email_token = Str::random(64);

		DB::table('email_verifications')->insert([
			'user_id' => $user->id,
			'token'   => $email_token,
		]);
		DB::commit();

		$token = auth()->login($user);

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
			'user'   => auth()->user(),
		], 200);
	}

	public function authorizedUser(): JsonResponse
	{
		$user = auth()->user();
		if ($user)
		{
			return response()->json(['user' => $user, 'status' => 'success'], 200);
		}
		return response()->json(['status' => 'error'], 401);
	}

	public function verify(string $token): JsonResponse
	{
		$email_token = EmailVerification::firstWhere('token', $token);
		if (!$email_token)
		{
			return response()->json(['status' => 'error'], 401);
		}
		$user = $email_token->user;
		$user->markEmailAsVerified();
		$user->save();
		$email_token->delete();
		return response()->json(['status' => 'success', 'user' => $user], 200);
	}
}
