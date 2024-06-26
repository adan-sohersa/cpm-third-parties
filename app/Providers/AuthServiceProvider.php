<?php

namespace App\Providers;

use App\Source\Authentication\Application\ValidateSession;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		//
	];

	/**
	 * Register any authentication / authorization services.
	 */
	public function boot(): void
	{
		Auth::viaRequest('authenticatorDriver', function (Request $request) {

			$sessionCookieName = str_replace(search: '.', replace: '_', subject: env('AUTHENTICATOR_APP_COOKIE_FOR_SESSION'));

			$sessionCookie = $request->cookie(key: $sessionCookieName);

			// Debugbar::debug('Request cookies => ' . $sessionCookie); // @debug

			return is_null($sessionCookie)
				? null
				: ValidateSession::isValidSession(sessionToken: $sessionCookie);
		});
	}
}
