<?php

namespace App\Source\Authorizations\Application;

use App\Source\Authorizations\Domain\IAuthorization;

class RefreshIAuthorization
{
	public static function refreshToken(IAuthorization $authorization)
	{
		if ($tokenRefresher = $authorization->determineTokenRefresher($authorization)) {
			return $tokenRefresher->refreshToken($authorization);
		} else {
			throw new \Exception("No token refresher available for authorization with provider " . $authorization->getProvider()->value);
		}
	}
}
