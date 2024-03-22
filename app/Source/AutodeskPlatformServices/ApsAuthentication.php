<?php

namespace App\Source\AutodeskPlatformServices;

use App\Enums\Authorization\ThirdPartyAuthorizables;
use Illuminate\Support\Facades\Http;

class ApsAuthentication
{

	const apiEndpoint = 'https://developer.api.autodesk.com/authentication/v2';

	const authorizeSufix = '/authorize';

	const tokenSufix = '/token';

	const userInfoEndpoint = 'https://api.userprofile.autodesk.com/userinfo';

	static function authorizationEndpoint(ThirdPartyAuthorizables $authorizable_type = ThirdPartyAuthorizables::USER, string $authorizable_id = null): string
	{
		$template = ApsAuthentication::apiEndpoint .
			ApsAuthentication::authorizeSufix .
			'?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&scope=$scope&state=$state';

		$state = array(
			'authorizable_type' => $authorizable_type->value,
			'authorizable_id' => $authorizable_id
		);

		$vars = array(
			'$client_id' => env('APS_CLIENT_ID'),
			'$scope' => env('APS_SCOPES'),
			'$redirect_uri' => env('APS_REDIRECT_URI'),
			'$state' => urlencode(
				json_encode($state)
			)
		);

		return strtr($template, $vars);
	}

	static function getTokenByAuthorizationCode(string $code): \Illuminate\Http\Client\Response
	{
		$url = ApsAuthentication::apiEndpoint . ApsAuthentication::tokenSufix;

		return Http::withBasicAuth(env('APS_CLIENT_ID'), env('APS_CLIENT_SECRET'))
			->acceptJson()
			->asForm()
			->post(url: $url, data: [
				'grant_type' => 'authorization_code',
				'code' => $code,
				'redirect_uri' => env('APS_REDIRECT_URI')
			]);
	}

	static function getUserByToken(string $token): \Illuminate\Http\Client\Response
	{
		return Http::acceptJson()
			->withToken($token)
			->get(url: ApsAuthentication::userInfoEndpoint);
	}
}
