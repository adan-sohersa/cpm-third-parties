<?php

namespace App\Source\ViewerStates\Application;

use App\Models\Authorization;
use App\Models\ViewerState;

/**
 * The object containing all the actions that can be performed on viewer states, such as creating them, renaming them, etc.
 * It includes the business logic of the application, such as a validation of the action.
 */
class ViewerStateActions
{

	/**
	 * Creates a new viewer state with the given params.
	 *
	 * @param string $name An arbitrary name for the viewer state.
	 * @param array $state The state to restore in the viewer.
	 * @param Authorization $authorization The authorization which is required to restore the viewer state.
	 * @return ViewerState The created viewer state.
	 */
	public static function create(
		string $name,
		string $state,
		Authorization $authorization,
	): ViewerState
	{
		// Creating a new viewer state with the given params.
		$viewerState = new ViewerState([
			'name' => $name,
			'state' => $state,
			'authorization_id' => $authorization->id,
		]);

		// Getting the amount of viewer states with the same name and the same authorization.
		$previousStates = ViewerStateQueries::statesWithTheSameNameFromAuthorizable($viewerState);

		// Setting the version of the viewer state.
		$viewerState->version = $previousStates->count() + 1;
		
		// Saving the viewer state.
		return ViewerStateCrudActions::save($viewerState);
	}

	/**
	 * Sets the new name in all the states which shares its name and its authorizable entity with the given one.
	 *
	 * @param ViewerState $viewerState The viewer state to use as reference.
	 * @param string $newName The new name to set in the states.
	 * @param boolean $skipAvailabilityCheck If true, the method will not check if the new name is available.
	 * @return \Illuminate\Support\Collection<int, \App\Models\ViewerState> A fresh collection with the renamed states.
	 */
	public static function renameStatesFromAuthorizable(ViewerState $viewerState, string $newName, bool $skipAvailabilityCheck = false): \Illuminate\Support\Collection
	{
		// Checking if the new name is not taken yet.
		if (!$skipAvailabilityCheck && !ViewerStateQueries::isTheNameAvailable($viewerState, $newName)) {
			// Throwing an exception if there are any viewer state with the new name from the same authorizable.
			throw new \Exception('The given name is already taken by another state.');
		}

		// Getting the viewer states with the same name, related to the same auhtorizable.
		$statesToRename = ViewerStateQueries::statesWithTheSameNameFromAuthorizable($viewerState);

		// Updating the name of all the states to rename.
		$amountOfUpdatedStates = ViewerStateCrudActions::renameViewerStates($statesToRename, $newName);

		// Throwing an exception if the amount of updated states is not the same as the amount of states to rename.
		if ($amountOfUpdatedStates != $statesToRename->count()) {
			throw new \Exception('Some versions of the state were not updated.');
		}

		// Returning a fresh collection with the renamed states.
		return ViewerStateCrudActions::refreshCollection($statesToRename);
	}

	/**
	 * Delete the given viewer state.
	 *
	 * @param ViewerState $viewerState The viewer state to delete.
	 * @param boolean $issueEvent If true, the corresponding events from the Eloquent ORM will be issued.
	 * @return \App\Models\ViewerState The deleted viewer state.
	 */
	public static function deleteVersion(ViewerState $viewerState, bool $issueEvent = false): \App\Models\ViewerState
	{
		// Handling the deletion when the event is required.
		if ($issueEvent) {

			// Deleting the viewer state.
			$deletedItem = ViewerStateCrudActions::deleteItem($viewerState);

			// Throwing an exception if no viewer state was returned by the method.
			if (!isset($deletedItem)) {
				throw new \Exception('The viewer state was not deleted.');
			}

			// Breaking the function.
			return $viewerState;
		}

		// Deleting the version when he event is not required.
		$amountOfDeletedStates = ViewerStateCrudActions::deleteCollection(
			viewerStates: collect([$viewerState]),
		);

		// Throwing an exception if the amount of deleted states is less than 1.
		if ($amountOfDeletedStates < 1) {
			throw new \Exception('The viewer state was not deleted.');
		}

		// Breaking the function.
		return $viewerState;
	}

	/**
	 * Deletes all the version of the given state.
	 *
	 * @param ViewerState $viewerState The viewer state to use as reference.
	 * @return \Illuminate\Support\Collection<int, \App\Models\ViewerState> The collection of deleted states.
	 */
	public static function deleteAllVersions(ViewerState $viewerState, bool $issueEvent = false): \Illuminate\Support\Collection
	{
		// Getting the amount of viewer states with the same name and the same authorization.
		$statesToDelete = ViewerStateQueries::statesWithTheSameNameFromAuthorizable($viewerState);

		// Deleting the states one by one if the corresponding events are required.
		if ($issueEvent) {

			// Deleting all the versions of the viewer state.
			foreach ($statesToDelete as $stateToDelete) {
				// Deleting the viewer state.
				ViewerStateCrudActions::deleteItem($stateToDelete);
			}

			// Returning the deleted states.
			return $statesToDelete;
		}

		// Deleting all the versions of the viewer state.
		$amountOfDeletedStates = ViewerStateCrudActions::deleteCollection($statesToDelete);

		// Throwing an exception if the amount of deleted states is not the same as the amount of states to delete.
		if ($amountOfDeletedStates != $statesToDelete->count()) {
			throw new \Exception('Some versions of the state were not deleted.');
		}

		// Returning the deleted states.
		return $statesToDelete;
	}

}
