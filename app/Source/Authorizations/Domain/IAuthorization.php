<?php

namespace App\Source\Authorizations\Domain;

use App\Enums\Authorization\ThirdPartyProviders;

/**
 * This interface defines the structure that an authorization must implement.
 * 
 */
interface IAuthorization extends IAuthorizationRepository
{

	/**
	 * This method will return the authorization's provider.
	 *
	 * @return ThirdPartyProviders The authorization's provider.
	 */
	public function getProvider(): ThirdPartyProviders;

	/**
	 * This method will determine the token refresher to use for the given authorization based on the authorization's provider.
	 *
	 * @param IAuthorization $authorization
	 * @return ITokenRefresher
	 */
	public function determineTokenRefresher(IAuthorization $authorization): ITokenRefresher;
}
