<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A snapshot of the state of a viewer that can be used to preserve it.
 * 
 * @property string $id The primary key of the instance.
 * @property string $name An arbitraty alias for identifying the state.
 * @property int $version The version of the state.
 * @property string $authorization_id The id of the authorization that this state belongs to.
 * @property mixed $state The setting to recover the state.
 * 
 * @property \App\Models\Authorization $authorization
 */
class ViewerState extends Model
{
	use HasFactory;

	use HasUuids;
	use SoftDeletes;

	protected $fillable = [
		'name',
		'version',
		'authorization_id',
		'state',
	];

	/**
	 * Get the authorization that this state belongs to.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function authorization(): BelongsTo
	{
		return $this->belongsTo(Authorization::class);
	}
}
