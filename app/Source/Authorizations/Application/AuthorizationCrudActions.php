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
	 * @param bool $throwException Whether to throw an exception if the authorization is not found.
	 * @return Authorization|null The authorization found.
	 */
	public static function findById(string $id = null, bool $throwException = false): Authorization|null
	{
		// Handling the error when the id is not set.
		if (!isset($id)) {
			// Throwing the exception if it is required.
			if ($throwException) {
				throw new \Exception('The id of the authorization to be found is required.');
			}
			// Returning null.
			return null;
		}

		// Trying to find the authorization.
		try {
			// Returning the authorization.
			return Authorization::findOrFail($id);
		} catch (\Throwable $th) {

			if ($throwException) {
				throw $th;
			}

			// Returning null.
			return null;
		}
	}
}
