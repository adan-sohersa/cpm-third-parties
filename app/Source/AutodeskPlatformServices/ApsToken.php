<?php

namespace App\Source\AutodeskPlatformServices;

use App\Enums\Authorization\ThirdPartyAuthorizables;
use App\Enums\Authorization\ThirdPartyProviders;
use App\Models\Authorization;
use App\Source\Authorizations\Domain\IExternalAuthorization;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ApsToken extends Model implements IExternalAuthorization
{
	protected $fillable = [
		'token_type',
		'expires_in',
		'refresh_token',
		'access_token',
		'id_token',
		'picture'
	];

	public function convertToInternal(ThirdPartyAuthorizables $authorizableType, string $authorizableId): Authorization
	{
		$authorization = new Authorization($this->toArray());
		$authorization['provider'] = ThirdPartyProviders::acc->value;
		$authorization['expires_at'] = Carbon::now()->timestamp + $this->expires_in;
		$authorization['scopes'] = env('APS_SCOPES');

		$userInfoResponse = ApsAuthentication::getUserByToken($authorization->access_token);
		$userInfoJson = $userInfoResponse->json();
		$authorization['username_at_provider'] = $userInfoJson['name'];
		$authorization['user_picture'] = $userInfoJson['picture'];

		$authorization['authorizable_class'] = $authorizableType->value;
		$authorization['authorizable_id'] = $authorizableId;

		$authorization->makeVisible(Authorization::HIDDEN_KEYS);

		return $authorization;
	}
}
