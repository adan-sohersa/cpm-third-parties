<?php

namespace App\Http\Controllers\Api\v1\Authentication;

use App\Http\Controllers\Controller;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiV1AuthenticationController extends Controller
{

	public function signout(Request $request)
	{
		// Building the session cookie name
		$sessionCookieName = str_replace(search: '.', replace: '_', subject: env('AUTHENTICATOR_APP_COOKIE_FOR_SESSION'));
		// Getting the session cookie from the request
		$sessionCookie = $request->cookie(key: $sessionCookieName);

		Debugbar::info(['sessionCookie' => $sessionCookie]);

		// Calling the authenticator app to delete the current session
		$response = Http::withCookies(
			cookies: [

				env('AUTHENTICATOR_APP_COOKIE_FOR_SESSION') => $sessionCookie
			],
			domain: '.' . env('MAIN_DOMAIN')
		)
			->get(url: env('AUTHENTICATOR_APP_URL') . env('AUTHENTICATOR_APP_SIGNOUT_ROUTE'));

			return response()->json([
				'response' => $response->json(),
				'body' => $response->body(),
				'headers' => $response->headers(),
				'status' => $response->status(),
				'cookies' => $response->cookies()->toArray()
			]);

		// Debugbar::info(

		// 	[
		// 		'response' => $response,
		// 		'response' => $response->json()
		// 	]
		// );

		// // @todo handle the response
		// if ($response->failed()) {
		// 	return response()->json([
		// 		'data' => 'Error while logging out',
		// 		'status' => 'error'
		// 	], 500);
		// }

		// return response()->json([
		// 	'data' => 'Logged out successfully',
		// 	'status' => 'success'
		// ]);
	}
}
