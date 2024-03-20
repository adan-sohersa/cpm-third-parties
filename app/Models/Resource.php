<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

		protected $fillable = [
			'authorization_id',
			'path_in_source',
			'id_in_source',
			'alias',
			'type'
		];
}
