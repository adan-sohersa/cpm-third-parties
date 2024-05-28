<?php

namespace App\Source\Authorizations\Infraestructure;

use App\Models\Authorization;
use App\Source\AutodeskPlatformServices\ApsToken;
use App\Source\AutodeskPlatformServices\Domain\ITokenRefresher;
use Illuminate\Support\Facades\Http;
use \App\Enums\Authorization\ThirdPartyAuthorizables;

class AutodeskTokenRefresher implements ITokenRefresher {

	public function refreshToken(Authorization $authorization) {
		// Se toman los datos de la cuenta de Autodesk
		$clientID = env('APS_CLIENT_ID');
		$clientSecret = env('APS_CLIENT_SECRET');

		// Se construye el cuerpo de la solicitud
		$body = [
			'grant_type' => 'refresh_token',
			'refresh_token' => $authorization->refresh_token,
		];

		// Se realiza la solicitu POST
		$response = Http::withBasicAuth($clientID, $clientSecret)->withHeaders([
			'Content-Type' => 'application/x-www-form-urlencoded'
		])->asForm()->post('https://developer.api.autodesk.com/authentication/v2/token', $body);

		if ($response->successful()) {
			$data = $response->json();
			$newAccessToken = new ApsToken($data);
			$newAccessToken = $newAccessToken->convertToInternal(authorizableId: $authorization->authorizable_id, authorizableType: ThirdPartyAuthorizables::from($authorization->authorizable_class));
			$authorization->update($newAccessToken->toArray());
	}
}
}