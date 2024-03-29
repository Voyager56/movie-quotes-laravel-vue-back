<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordChangeRequest;
use App\Http\Requests\sendPasswordResetEmailRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
	public function sendPasswordResetEmail(sendPasswordResetEmailRequest $request): JsonResponse
	{
		$user = User::firstWhere('email', $request->email);

		$token = Str::random(64);
		DB::table('password_resets')->insert([
			'email'      => $request->email,
			'token'      => $token,
			'created_at' => now(),
		]);

		Mail::send('ResetPassword', [
			'url'      => url(env('FRONT_END') . '/reset-password/' . $token),
			'username' => $user->username,
		], function ($message) use ($user) {
			$message->to($user->email);
			$message->subject('Password Reset');
		});

		return response()->json('Email sent', 200);
	}

	public function resetPassword(string $token, PasswordChangeRequest $request): JsonResponse
	{
		$requestUser = DB::table('password_resets')->where('token', $token)->first();

		if (!$requestUser)
		{
			return response()->json('Invalid token', 401);
		}
		$user = User::firstWhere('email', $requestUser->email);
		$user->update(['password' => bcrypt($request->password)]);

		DB::table('password_resets')->where(['email'=> $user->email])->delete();

		$token = auth()->login($user);

		return response()->json(['token' => $token], 200);
	}
}
