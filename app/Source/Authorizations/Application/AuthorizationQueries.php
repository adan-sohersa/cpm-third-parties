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
	 * Get all the authorizations which shares authorizable with the given one.
	 *
	 * @param string $authorizableClass The authorizable class to use as reference.
	 * @param string $authorizableId The authorizable id to use as reference.
	 * @return \Illuminate\Support\Collection<int, Authorization>
	 */
	public static function authorizationsFromAuthorizable(string $authorizableClass, string $authorizableId): \Illuminate\Support\Collection
	{
		// Throwing an exception if the references are not set yet.
		if (!isset($authorizableClass) || !isset($authorizableId))
		{
			throw new \Exception('Missing references while getting authorizations from authorizable.');
		}

		// Returning the result of the query.
		return Authorization::where('authorizable_id', $authorizableId)
			->where('authorizable_class', $authorizableClass)
			->get();
	}
}
