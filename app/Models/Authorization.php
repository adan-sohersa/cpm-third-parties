<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use App\Enums\Authorization\ThirdPartyProviders;
use App\Source\Authorizations\Domain\IAuthorization;
use App\Source\Authorizations\Domain\ITokenRefresher;
use App\Source\Authorizations\Infraestructure\AutodeskTokenRefresher;

/**
 * @todo Implement a dictionary of query params use along the the app in order to map the authorizable_class and the authorizable_id attributes.
 * 
 * @property \Illuminate\Support\Collection<int, \App\Models\ViewerState> $viewerStates The viewer states associated with the authorization.
 */
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

	/**
	 * Get all of the viewer states for the Authorization
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function viewerStates(): HasMany
	{
		return $this->hasMany(related: ViewerState::class);
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

	/**
	 * Determines if the token is active at the moment of the request.
	 * 
	 * @return bool
	 */
	protected function isTokenActive(): Attribute
	{
		return Attribute::make(
			get: function () {
				$expiresAt = Carbon::createFromTimestamp($this->expires_at);
				return Carbon::now()->isBefore($expiresAt);
			},
		);
	}
}
