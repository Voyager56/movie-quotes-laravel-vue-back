<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{

	public function redirectToGoogle(): JsonResponse
	{
		$url =  Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

		return response()->json(['url' => $url]);
	}

	public function handleProviderCallback()
	{
		$googleUser = Socialite::driver('google')->stateless()->user();

		$user = User::where('email', $googleUser->email)->first();
		if ($user) {
			$token = auth()->setTTL(60)->attempt([
				'email' => $googleUser->email,
				'password' => $googleUser->name . "@" . $googleUser->id,
			]);
	
			return redirect()->away(env("FRONT_END"). "/?token=" . $token);
		}

		$user = User::updateOrCreate([
			'username' => $googleUser->name,
			"email_verified_at" => now(),
			'email' => $googleUser->email,
			'password' => bcrypt($googleUser->name . "@" . $googleUser->id),
			'photo' => $googleUser->avatar,
		]);
		$user->markEmailAsVerified();
		$token = auth()->setTTL(60)->attempt([
			'email' => $googleUser->email,
			'password' => $googleUser->name . "@" . $googleUser->id,
		]);

		return redirect()->away(env("FRONT_END"). "/?token=" . $token);

	}
}