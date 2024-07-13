<?php

namespace App\Source\Authorizations\Application;

use App\Models\Authorization;

/**
 * An object that contains the queries that can be made to the authorizations table.
 * The aim of this class is to centralize the definition of the queries and
 * avoid repeating them in the different places where they are needed.
 */
class AuthorizationQueries
{

	/**
	 * Get all the authroizations that shares authorizable with the given one.
	 *
	 * @param Authorization $authorization
	 * @return \Illuminate\Support\Collection<int, Authorization>
	 */
	public static function authorizationsFromAuthorizable(Authorization $authorization): \Illuminate\Support\Collection
	{
		return Authorization::where('authorizable_id', $authorization->authorizable_id)
			->where('authorizable_class', $authorization->authorizable_class)
			->get();
	}
}
