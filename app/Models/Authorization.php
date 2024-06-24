<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use App\Enums\Authorization\ThirdPartyProviders;
use App\Source\Authorizations\Domain\IAuthorization;
use App\Source\Authorizations\Domain\ITokenRefresher;
use App\Source\Authorizations\Infraestructure\AutodeskTokenRefresher;

class Authorization extends Model implements IAuthorization
{
	use HasUuids;
	use HasFactory;
	use SoftDeletes;

	protected $fillable = [
		'provider',
		'access_token',
		'refresh_token',
		'scopes',
		'expires_at',
		'username_at_provider',
		'user_picture',
		'authorizable_class',
		'authorizable_id'
	];

	protected $hidden = [
		'refresh_token',
		'expires_at',
		'authorizable_class',
		'authorizable_id'
	];

	const HIDDEN_KEYS = [
		'refresh_token',
		'expires_at',
		'authorizable_class',
		'authorizable_id'
	];

	const UNIQUE_KEYS = [
		'provider',
		'scopes',
		'username_at_provider',
		'authorizable_class',
		'authorizable_id'
	];

	/**
	 * Get all of the resources for the Authorization
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function resources(): HasMany
	{
		return $this->hasMany(related: Resource::class);
	}

	public function getProvider(): ThirdPartyProviders
	{
		return ThirdPartyProviders::from($this->provider);
	}

	public function determineTokenRefresher(IAuthorization $authorization): ITokenRefresher

	{
		try {
			switch ($authorization->getProvider()) {
				case ThirdPartyProviders::acc:
					return new AutodeskTokenRefresher();
				default:
					return null;
			}
		} catch (\Exception $e) {
			return null;
		}
	}
}
