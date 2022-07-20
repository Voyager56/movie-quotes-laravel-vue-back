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
		$googleUser = Socialite::driver('google')->user();
		$user = User::updateOrCreate([
			'username' => $googleUser->name,
			'email' => $googleUser->email,
			'password' => bcrypt($googleUser->id),
			'email_verified_at' => now(),
		]);
		$token = auth()->setTTL(60)->attempt([
			'email' => $googleUser->email,
			'password' => $googleUser->id,
		]);

	}
}
