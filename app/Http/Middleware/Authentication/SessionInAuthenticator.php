<?php

namespace App\Http\Middleware\Authentication;

use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class SessionInAuthenticator
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{

		$sessionCookieName = str_replace(search: '.', replace: '_', subject: env('AUTHENTICATOR_APP_COOKIE_FOR_SESSION'));

		$sessionCookie = $request->cookie(key: $sessionCookieName);

		if (is_null($sessionCookie)) {
			return $this->redirectToAuthenticator($request->fullUrl());
		}

		$user = $this->isValidSession(sessionToken: $sessionCookie);

		if (is_null($user)) {
			return $this->redirectToAuthenticator($request->fullUrl());
		}

		$request->session()->put(key: 'user', value: $user);

		return $next($request);
	}

	private function isValidSession(string $sessionToken = null): \App\Models\User | null
	{
		Debugbar::debug($sessionToken);

		$endpoint = env('AUTHENTICATOR_APP_URL') . env('AUTHENTICATOR_APP_SESSION_ENDPOINT');

		$response = Http::withCookies(
			cookies: [
				env('AUTHENTICATOR_APP_COOKIE_FOR_SESSION') => $sessionToken
			],
			domain: '.sohersabim.test'
		)->get(url: $endpoint);

		if ($response->failed()) {
			return null;
		}

		$responseJson = $response->json();

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

	private function redirectToAuthenticator(string $redirectionBack)
	{
		$redirectionCookieName = env('AUTHENTICATOR_APP_COOKIE_FOR_REDIRECTION');

		$cookieDomain = env('MAIN_DOMAIN') && env('MAIN_DOMAIN') !== 'localhost'
			? '.' . env('MAIN_DOMAIN')
			: 'localhost';

		$redirectionCookie = cookie(
			name: $redirectionCookieName,
			value: $redirectionBack,
			domain: $cookieDomain,
		);

		$endpoint = env('AUTHENTICATOR_APP_URL') . env('AUTHENTICATOR_APP_SIGNING_ROUTE');

		return redirect(to: $endpoint)->withCookie(cookie: $redirectionCookie);
	}
}
