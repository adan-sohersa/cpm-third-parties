<?php

namespace App\Source\Authorizations\Application;

use App\Enums\Authorization\ThirdPartyAuthorizables;
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
		if (!isset($authorizableClass) || !isset($authorizableId)) {
			throw new \Exception('Missing references while getting authorizations from authorizable.');
		}

		// Returning the result of the query.
		return Authorization::where('authorizable_id', $authorizableId)
			->where('authorizable_class', $authorizableClass)
			->get();
	}

	/**
	 * Determines if the authorization is attached to the given user.
	 *
	 * @param \App\Models\Authorization $authorization The authorization to check.
	 * @param \App\Models\User $user The user to check.
	 * @return bool
	 */
	public static function belongsToUser(\App\Models\Authorization $authorization, \App\Models\User $user): bool
	{
		// Throwing an exception if the authorization does not belong to the user.
		if ($authorization->authorizable_class !== ThirdPartyAuthorizables::USER->value) {
			return false;
		}

		return $authorization->authorizable_id === $user->id;
	}

	/**
	 * Determines if the authorization could be implemented by the given user.
	 *
	 * @param \App\Models\Authorization $authorization The authorization to check.
	 * @param \App\Models\User $user The user to check.
	 * @return bool
	 */
	public static function couldBeImplementedByUser(\App\Models\Authorization $authorization, \App\Models\User $user, bool $reasonInException = false): bool
	{
		// Determining if the authorization belongs to the user.
		$belongsToUser = AuthorizationQueries::belongsToUser($authorization, $user);

		// Throwing the corresponding exception if required.
		if (!$belongsToUser && $reasonInException) {
			throw new \Exception('The authorization could not be implemented by the user.');
		}

		return true;
	}
}
