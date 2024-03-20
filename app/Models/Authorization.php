<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Authorization extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $fillable = [
		'provider',
		'access_token',
		'refresh_token',
		'expires_at',
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
