<?php

namespace App\Policies\Api\v1;

use App\Models\Authorization;
use App\Models\User;
use App\Models\ViewerState;
use App\Source\Authorizations\Application\AuthorizationQueries;
use Illuminate\Auth\Access\Response;

class ViewerStatePolicy
{
	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): Response
	{
		return Response::deny('Policy not implemented');
	}

	/**
	 * Determine whether the user can view a viewer state.
	 */
	public function view(User $user, ViewerState $viewerState): Response
	{
		try {
			// Getting the authorization associated with the viewer state
			$authorization = $viewerState->authorization;
		
			if (AuthorizationQueries::couldBeAccessedByUser(authorization: $authorization, user: $user, reasonInException: true)) {
				return Response::allow();
			}
		} catch (\Throwable $th) {
			return Response::deny($th->getMessage());
		}
	}

	/**
	 * Determine whether the user can create a viewer state associated with the given authorization.
	 * 
	 * @param \App\Models\User $user The user to authorize.
	 * @param \App\Models\Authorization $authorization The authorization to which the viewer state will be associated.
	 */
	public function create(User $user, Authorization $authorization) : Response
	{
		try {
			if (AuthorizationQueries::couldBeImplementedByUser(authorization: $authorization, user: $user, reasonInException: true)) {
				return Response::allow();
			}
		} catch (\Throwable $th) {
			return Response::deny($th->getMessage());
		}
	}

	/**
	 * Determine whether the user can update a viewer state associated with the given authorization.
	 * 
	 * @param \App\Models\User $user The user to authorize.
	 * @param \App\Models\ViewerState $viewerState The viewer state to update.
	 */
	public function update(User $user, ViewerState $viewerState) : Response
	{
		try {
			if (AuthorizationQueries::couldBeImplementedByUser(authorization: $viewerState->authorization, user: $user, reasonInException: true)) {
				return Response::allow();
			}
		} catch (\Throwable $th) {
			return Response::deny($th->getMessage());
		}
	}

	/**
	 * Determine whether the user can delete a viewer state associated with the given authorization.
	 * 
	 * @param \App\Models\User $user The user to authorize.
	 * @param \App\Models\ViewerState $viewerState The viewer state to update.
	 */
	public function delete(User $user, ViewerState $viewerState) : Response
	{
		try {
			if (AuthorizationQueries::couldBeImplementedByUser(authorization: $viewerState->authorization, user: $user, reasonInException: true)) {
				return Response::allow();
			}
		} catch (\Throwable $th) {
			return Response::deny($th->getMessage());
		}
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, ViewerState $viewerState): Response
	{
		return Response::deny('Policy not implemented');
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, ViewerState $viewerState): Response
	{
		return Response::deny('Policy not implemented');
	}
}
