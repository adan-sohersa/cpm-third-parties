<?php

namespace App\Source\Authorizations\Infraestructure;

use App\Source\AutodeskPlatformServices\ApsToken;
use App\Source\Authorizations\Domain\ITokenRefresher;
use Illuminate\Support\Facades\Http;
use \App\Enums\Authorization\ThirdPartyAuthorizables;
use App\Source\Authorizations\Domain\IAuthorization;

class AutodeskTokenRefresher implements ITokenRefresher
{

	public function refreshToken(IAuthorization $authorization)
	{
		// Set both the client id and secret from the .env file
		$clientID = env('APS_CLIENT_ID');
		$clientSecret = env('APS_CLIENT_SECRET');

		// Build the body of the request
		$body = [
			'grant_type' => 'refresh_token',
			'refresh_token' => $authorization->refresh_token,
		];

		// Make the request to the Autodesk Platform Services API
		$response = Http::withBasicAuth($clientID, $clientSecret)
			->withHeaders([
				'Content-Type' => 'application/x-www-form-urlencoded'
			])
			->asForm()
			->post('https://developer.api.autodesk.com/authentication/v2/token', $body);

		// Handle the successful response
		if ($response->successful()) {
			// Parse the response body to an array
			$data = $response->json();
			// Create a new APS token object with the parsed data
			$newAccessToken = new ApsToken($data);
			// Convert the new access token to an internal representation
			$newAccessToken = $newAccessToken->convertToInternal(
				authorizableId: $authorization->authorizable_id,
				authorizableType: ThirdPartyAuthorizables::from($authorization->authorizable_class)
			);
			// Update the authorization with the new access token
			$authorization->update($newAccessToken->toArray());

			// Return the updated authorization
			return $authorization;
		}

		throw new \Exception('Unable to refresh the token.');
	}
}
