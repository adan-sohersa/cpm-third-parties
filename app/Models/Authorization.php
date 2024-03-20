<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
