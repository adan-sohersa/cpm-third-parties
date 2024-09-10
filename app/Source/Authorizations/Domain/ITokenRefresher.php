<?php

namespace App\Source\Authorizations\Domain;

use App\Source\Authorizations\Domain\IAuthorization;

/**
 * This interface defines the methods that a token refresher must implement.
 */
interface ITokenRefresher
{
	/**
	 * This method will refresh the token of the authorization, but it will not update the instance.
	 *
	 * @method App\Models\Authorization refreshToken(App\Models\Authorization $authorization)
	 * @param IAuthorization $authorization The authorization with the token to refresh.
	 * @return IAuthorization The authorization with the new token.
	 */
	public function refreshToken(IAuthorization $authorization);
}
