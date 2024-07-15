<?php

namespace App\Source\ViewerStates\Application;

use App\Enums\Authorization\ThirdPartyAuthorizables;
use App\Models\ViewerState;
use App\Source\Authorizations\Application\AuthorizationQueries;

/**
 * An object that contains the queries that can be made to the 'viewer_states' table.
 * The aim of this class is to centralize the definition of the queries and
 * avoid repeating them in the different places where they are needed.
 */
class ViewerStateQueries
{

	/**
	 * Get all the viewer states which shares authorization with the given one.
	 *
	 * @param \App\Models\ViewerState $viewerState The
	 * @return \Illuminate\Support\Collection<int, \App\Models\ViewerState>
	 */
	public static function statesFromAuthorization(ViewerState $viewerState): \Illuminate\Support\Collection
	{
		return $viewerState->authorization->viewerStates;
	}

	/**
	 * Get all the viewer states related to the corresponding authorizable entity.
	 *
	 * @param ThirdPartyAuthorizables $authorizableClass The king of the authorizable.
	 * @param string $authorizableId The id of the authorizable.
	 * @return \Illuminate\Support\Collection<int, \App\Models\ViewerState>
	 */
	public static function statesFromAuthorizable(ThirdPartyAuthorizables $authorizableClass, string $authorizableId): \Illuminate\Support\Collection
	{
		// Getting the authorizations of the authorizable.
		$authorizations = AuthorizationQueries::authorizationsFromAuthorizable(authorizableClass: $authorizableClass->value, authorizableId: $authorizableId);

		// Loading the viewer states of the authorizations.
		$authorizations->load('viewerStates');

		// Getting the viewer states by:
		return $authorizations
			// Abstracting the viewer states of the authorizations.
			->pluck(value: 'viewerStates')
			// Flattening the collection.
			->collapse()
			// Removing the duplicated ones.
			->unique(key: 'id');
	}

	/**
	 * Get all the viewer states which shares the same name and authorization with the given one.
	 *
	 * @param ViewerState $viewerState The viewer state to use as reference.
	 * @return \Illuminate\Support\Collection<int, \App\Models\ViewerState>
	 */
	public static function statesWithTheSameNameFromAuthorization(ViewerState $viewerState): \Illuminate\Support\Collection
	{
		return ViewerState::where('name', $viewerState->name)
			->where('authorization_id', $viewerState->authorization_id)
			->get();
	}

	/**
	 * Get all the viewer states with the same name and authorized entity as the given one.
	 *
	 * @param ViewerState $viewerState The viewer state to use as reference.
	 * @return \Illuminate\Support\Collection<int, \App\Models\ViewerState>
	 */
	public static function statesWithTheSameNameFromAuthorizable(ViewerState $viewerState): \Illuminate\Support\Collection
	{
		// Getting the viewer states from the given authorizable.
		$viewerStates = ViewerStateQueries::statesFromAuthorizable(
			authorizableClass: $viewerState->authorization->authorizable_class,
			authorizableId: $viewerState->authorization->authorizable_id
		);

		// Returning just the viewer states with the same name.
		return $viewerStates->where(key: 'name', operator: '=', value: $viewerState->name);
	}

	/**
	 * Determines if the given name has not been taken yet by another state related to the same authorizable entity.
	 *
	 * @param ViewerState $viewerState The viewer state to use as reference.
	 * @param string $name The name to check.
	 * @return boolean True if the name is available, false otherwise.
	 */
	public static function isTheNameAvailable(ViewerState $viewerState, string $name): bool
	{
		// Replicating the given instance; with the new name, though.
		$temporalState = $viewerState
			->replicate()
			->fill([
				'name' => $name
			]);

		// Getting the viewer states with the given name, from the same authorizable.
		$previousStates = ViewerStateQueries::statesWithTheSameNameFromAuthorizable($temporalState);

		// Determining if the name is available.
		return $previousStates->count() == 0;
	}
}
