<?php

namespace App\Source\ViewerStates\Application;

use App\Models\ViewerState;

/**
 * The object that contains the actions that can be performed to the 'viewer_states' table.
 * The aim of this class is no one but centralize the definition of the actions,
 * avoiding repeating them in the different places where they are needed.
 * It doesn't takes care of the domain logic, but only the data transactions.
 */
class ViewerStateCrudActions
{

	/**
	 * Preserves the given instance in the database.
	 *
	 * @param ViewerState $viewerState The viewer state to save.
	 * @return ViewerState
	 */
	public static function save(ViewerState $viewerState): ViewerState
	{
		$viewerState->save();
		return $viewerState;
	}

	/**
	 * Refreshes the given viewer state.
	 *
	 * @param ViewerState $viewerState The viewer state to refresh.
	 * @return ViewerState The refreshed viewer state.
	 */
	public static function refresItem(ViewerState $viewerState): ViewerState
	{
		return $viewerState->refresh();
	}

	/**
	 * Makes a new query for the given viewer states, incliding the requested relations.
	 *
	 * @param \Illuminate\Support\Collection $viewerStates The viewer states to refresh.
	 * @param array $with The relations to include in the query.
	 * @return \Illuminate\Support\Collection<int, \App\Models\ViewerState> The executed query.
	 */
	public static function refreshCollection(\Illuminate\Support\Collection $viewerStates, array $with = []): \Illuminate\Support\Collection
	{
		// Getting the ids of the viewer states.
		$ids = $viewerStates->pluck(value: 'id')->toArray();

		// Initializing the query builder.
		$query = ViewerState::whereIn('id', $ids);

		// Including the requested relations.
		if (count($with) > 0) {
			$query->with($with);
		}

		// Returning the executed query.
		return $query->get();
	}

	/**
	 * Updates the given instances with the new name
	 *
	 * @param \Illuminate\Support\Collection<int, \App\Models\ViewerState> $viewerStates The instances to update.
	 * @param string $newName The new name to set in the instances.
	 * @return int The amount of updated instances.
	 */
	public static function renameViewerStates(\Illuminate\Support\Collection $viewerStates, string $newName): int
	{
		// Abstracting the ids of the states to update.
		$ids = $viewerStates
			->pluck(value: 'id')
			->toArray();

		// Updating the states with the new name.
		return ViewerState::whereIn('id', $ids)
			->update([
				'name' => $newName
			]);
	}

	/**
	 * Deletes the given viewer state.
	 *
	 * @param ViewerState $viewerState The instance to delete.
	 * @return ViewerState The deleted instance.
	 */
	public static function deleteItem(ViewerState $viewerState): ViewerState
	{
		$wasDeleted = $viewerState->delete();

		return $wasDeleted
			? $viewerState
			: null;
	}

	/**
	 * Deletes the given viewer states without issuing any event.
	 * If you want to delete the viewer states and issue an event, use the deleteItem method for each instance.
	 * 
	 * @param \Illuminate\Support\Collection $viewerStates A collection with the instances to delete.
	 * @return integer The amount of deleted instances.
	 */
	public static function deleteCollection(\Illuminate\Support\Collection $viewerStates): int
	{
		// Getting the ids of the viewer states.
		$ids = $viewerStates
			->pluck(value: 'id')
			->toArray();

		// Deleting the viewer states.
		return ViewerState::whereIn('id', $ids)
			->delete();
	}
}
