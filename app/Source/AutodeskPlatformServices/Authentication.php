<?php

namespace App\Source\AutodeskPlatformServices;

use Illuminate\Support\Facades\Http;

class Authentication
{

	const apiEndpoint = 'https://developer.api.autodesk.com/authentication/v2';

	const authorizeSufix = '/authorize';

	const tokenSufix = '/token';

	static function authorizationEndpoint(): string
	{
		$template = Authentication::apiEndpoint .
			Authentication::authorizeSufix .
			'?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&scope=$scope';

		$vars = array(
			'$client_id' => env('APS_CLIENT_ID'),
			'$redirect_uri' => env('APS_REDIRECT_URI'),
			'$scope' => env('APS_SCOPES')
		);

		return strtr($template, $vars);
	}

	static function getTokenByAuthorizationCode(string $code): \Illuminate\Http\Client\Response
	{
		$url = Authentication::apiEndpoint . Authentication::tokenSufix;

		return Http::withBasicAuth(env('APS_CLIENT_ID'), env('APS_CLIENT_SECRET'))
			->acceptJson()
			->asForm()
			->post(url: $url, data: [
				'grant_type' => 'authorization_code',
				'code' => $code,
				'redirect_uri' => env('APS_REDIRECT_URI')
			]);
	}
}
