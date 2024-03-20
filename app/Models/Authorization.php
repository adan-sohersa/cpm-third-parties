<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authorization extends Model
{
	use HasFactory;

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
