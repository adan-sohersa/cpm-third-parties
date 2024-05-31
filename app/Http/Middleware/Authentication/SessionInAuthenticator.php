<?php

namespace App\Http\Middleware\Authentication;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Cookie;
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
		if (!Auth::guard('authenticator')->check()) {
			return $this->redirectToAuthenticator(redirectionBack: $request->fullUrl());
		}

		return $next($request);
	}

	private function redirectToAuthenticator(string $redirectionBack)
	{
		$redirectionCookieName = env('AUTHENTICATOR_APP_COOKIE_FOR_REDIRECTION');

		$cookieDomain = env('MAIN_DOMAIN') && env('MAIN_DOMAIN') !== 'localhost'
			? '.' . env('MAIN_DOMAIN')
			: 'localhost';

		$redirectionCookie = Cookie::create(
			name: $redirectionCookieName,
			value: $redirectionBack,
			domain: $cookieDomain,
		);

		$endpoint = env('AUTHENTICATOR_APP_URL') . env('AUTHENTICATOR_APP_SIGNING_ROUTE');

		return redirect(to: $endpoint)->withCookie(cookie: $redirectionCookie);
	}
}
