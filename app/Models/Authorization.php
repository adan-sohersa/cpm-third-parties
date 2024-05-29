<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @todo Implement a dictionary of query params use along the the app in order to map the authorizable_class and the authorizable_id attributes.
 */
class Authorization extends Model
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
}
