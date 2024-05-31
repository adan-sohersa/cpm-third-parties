<?php

namespace App\Source\Authentication\Application;

use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Http;

class ValidateSession
{

	public static function isValidSession(string $sessionToken = null): \App\Models\User | null
	{

		$endpoint = env('AUTHENTICATOR_APP_URL') . env('AUTHENTICATOR_APP_SESSION_ENDPOINT');

		$response = Http::withCookies(
				cookies: [
					env('AUTHENTICATOR_APP_COOKIE_FOR_SESSION') => $sessionToken
				],
				domain: '.' . env('MAIN_DOMAIN')
			)->get(url: $endpoint);

		// Debugbar::info("Response => "); // @debug
		// Debugbar::info($response); // @debug
		// Debugbar::info($response->body()); // @debug

		if ($response->failed()) {
			return null;
		}

		$responseJson = $response->json();

		// Debugbar::info("Response Json => "); // @debug
		// Debugbar::info($responseJson); // @debug

		if (empty($responseJson)) {
			return null;
		}

		$authenticatedUser = $responseJson['user'];

		if (is_null($authenticatedUser)) {
			return null;
		}

		$user = new User($authenticatedUser);
		$user->id = $authenticatedUser['id'];

		return $user;
	}
}
