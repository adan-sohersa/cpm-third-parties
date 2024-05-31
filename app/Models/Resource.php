<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $fillable = [
		'authorization_id',
		'path_in_source',
		'id_in_source',
		'alias',
		'type'
	];

	/**
	 * Get the authorization that owns the Resource
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function authorization(): BelongsTo
	{
		return $this->belongsTo(related: Authorization::class);
	}
}
