<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Source\Authentication\Application\ValidateSession;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        Auth::viaRequest('authenticatorDriver', function (Request $request){

					$sessionCookieName = str_replace(search: '.', replace: '_', subject: env('AUTHENTICATOR_APP_COOKIE_FOR_SESSION'));

					// Log::debug('Request cookies => ', $request->cookie()); // @debug
					// Log::debug('Request cookies => '. $request->cookie(key: $sessionCookieName)); // @debug
					
					$sessionCookie = $request->cookie(key: $sessionCookieName);
					
					Log::debug('Request cookies => '. $sessionCookie); // @debug

					if (is_null($sessionCookie)) {
						return null;
					}

					$user = ValidateSession::isValidSession(sessionToken: $sessionCookie);

					Log::debug('User => ', $user->toArray()); // @debug

					return $user;

					// return is_null($sessionCookie)
					// 	? null
					// 	: ValidateSession::isValidSession(sessionToken: $sessionCookie);

				});
    }
}
