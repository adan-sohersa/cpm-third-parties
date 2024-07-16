<?php

namespace App\Source\Authorizations\Application;

use App\Models\Authorization;

/**
 * The actions that can be performed on an authorization.
 * The aim of this class is no one but centralize the definition of the actions,
 * avoiding repeating them in all the places they are needed.
 * It doesn't take care of the domain logic, only the data transactions.
 */
class AuthorizationCrudActions
{

	/**
	 * Finds an authorization by its id.
	 *
	 * @param string $id The primary key of the authorization to be found.
	 * @return Authorization The authorization found.
	 */
	public static function findById(string $id): Authorization
	{
		return Authorization::find($id);
	}
}
