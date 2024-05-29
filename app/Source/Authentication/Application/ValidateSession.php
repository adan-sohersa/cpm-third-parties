<?php

namespace App\Source\Authentication\Application;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ValidateSession {

	public static function isValidSession(string $sessionToken = null): \App\Models\User | null
	{

		$endpoint = env('AUTHENTICATOR_APP_URL') . env('AUTHENTICATOR_APP_SESSION_ENDPOINT');

		Log::debug("Endpoint => " . $endpoint);	

		$response = Http::withCookies(
			cookies: [
				env('AUTHENTICATOR_APP_COOKIE_FOR_SESSION') => $sessionToken
			],
			domain: '.' . env('MAIN_DOMAIN')
		)->get(url: $endpoint);

		if ($response->failed()) {
			return null;
		}

		Log::debug("Response => ", $response->body()); // @debug

		$responseJson = $response->json();

		Log::debug("Response Json => ", $responseJson); // @debug

		if (empty($responseJson)) {
			return null;
		}

		$authenticatedUser = $responseJson['user'];

		Log::debug("Authenticated User => ", $authenticatedUser); // @debug

		if (is_null($authenticatedUser)) {
			return null;
		}

		$user = new User($authenticatedUser);
		$user->id = $authenticatedUser['id'];

		return $user;

	}

}