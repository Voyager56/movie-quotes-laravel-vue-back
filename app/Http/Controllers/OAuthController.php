<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
	public function redirectToGoogle(): JsonResponse
	{
		$url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

		return response()->json(['url' => $url], 200);
	}

	public function handleProviderCallback(): JsonResponse
	{
		$googleUser = Socialite::driver('google')->stateless()->user();

		$user = User::firstWhere('email', $googleUser->email);
		if ($user)
		{
			$token = auth()->setTTL(60)->attempt([
				'email'    => $googleUser->email,
				'password' => $googleUser->name . '@' . $googleUser->id,
			]);

			return redirect()->away(env('FRONT_END') . '/?token=' . $token);
		}

		$user = User::updateOrCreate([
			'username'          => $googleUser->name,
			'email_verified_at' => now(),
			'email'             => $googleUser->email,
			'password'          => bcrypt($googleUser->name . '@' . $googleUser->id),
			'photo'             => $googleUser->avatar,
			'oauth'             => 1,
		]);
		$user->markEmailAsVerified();
		$token = auth()->setTTL(60)->attempt([
			'email'    => $googleUser->email,
			'password' => $googleUser->name . '@' . $googleUser->id,
		]);

		return redirect()->away(env('FRONT_END') . '/?token=' . $token);
	}
}
