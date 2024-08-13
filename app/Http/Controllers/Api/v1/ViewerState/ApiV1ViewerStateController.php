<?php

namespace App\Http\Controllers\Api\v1\ViewerState;

use App\Enums\Authorization\ThirdPartyAuthorizables;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ViewerState\DeleteViewerStateRequest;
use App\Http\Requests\v1\ViewerState\StoreViewerStateRequest;
use App\Http\Requests\v1\ViewerState\UpdateViewerStateRequest;
use App\Http\Requests\v1\ViewerState\UserViewerStateRequest;
use App\Http\Resources\Api\v1\ViewerStateCollection;
use App\Http\Resources\Api\v1\ViewerStateResource;
use App\Models\ViewerState;
use App\Source\Authorizations\Application\AuthorizationCrudActions;
use App\Source\ViewerStates\Application\ViewerStateActions;
use App\Source\ViewerStates\Application\ViewerStateCrudActions;
use App\Source\ViewerStates\Application\ViewerStateQueries;
use Illuminate\Support\Facades\Auth;

class ApiV1ViewerStateController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(UserViewerStateRequest $request)
	{
		/**
		 * Getting the authenticated user
		 * @var \App\Models\User $user
		 */
		$user = Auth::guard(name: 'authenticator')->user();

		// Getting viewer states from the user
		$viewerStates = ViewerStateQueries::statesFromAuthorizable(ThirdPartyAuthorizables::USER, $user->id);

		// Returning the viewer states
		return new ViewerStateCollection(resource: $viewerStates);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreViewerStateRequest $request)
	{
		// Getting the authorization which the viewer state will be associated to.
		$authorization = AuthorizationCrudActions::findById($request->validated('authorization_id'));

		/**
		 * Getting the authenticated user
		 * @var \App\Models\User $user
		 */
		$user = Auth::guard(name: 'authenticator')->user();

		// Authorizing the request
		$this->authorizeForUser(user: $user, ability: 'create', arguments: [ViewerState::class, $authorization]);

		// Creating the viewer state
		$state = ViewerStateActions::create(
			name: $request->validated('name'),
			state: $request->validated('state'),
			authorization: $authorization
		);

		// Loading the authorization
		$state->load(['authorization']);

		// Returning the viewer state
		return new ViewerStateResource(resource: $state);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(ViewerState $viewerState)
	{
		/**
		 * Getting the authenticated user
		 * @var \App\Models\User $user
		 */
		$user = Auth::guard(name: 'authenticator')->user();

		// Authorizing the request
		$this->authorizeForUser(user: $user, ability: 'view', arguments: [$viewerState]);

		// Loading the authorization associated with the viewer state
		$viewerState->load(['authorization']);

		// Returning the viewer state
		return new ViewerStateResource(resource: $viewerState);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateViewerStateRequest $request, ViewerState $viewerState)
	{
		/**
		 * Getting the authenticated user
		 * @var \App\Models\User $user
		 */
		$user = Auth::guard(name: 'authenticator')->user();

		// Authorizing the request
		$this->authorizeForUser(user: $user, ability: 'update', arguments: [$viewerState]);

		// Updating the accesibility of the viewer state if the 'is_public' parameter is provided.
		if ($request->validated('is_public') !== null && $request->validated('is_public') !== $viewerState->is_public) {
			$viewerState = ViewerStateActions::publish(
				viewerState: $viewerState,
				publish: $request->validated('is_public')
			);
		}

		// Renaming all the versions of the viewer state if the 'name' parameter is provided.
		if ($request->validated('name')) {
			ViewerStateActions::renameStatesFromAuthorizable(
				viewerState: $viewerState,
				newName: $request->validated('name'),
				// Skipping the availability check because the name is already validated.
				skipAvailabilityCheck: true
			);
		}

		// Refreshing the given viewer state.
		$refreshedInstance = ViewerStateCrudActions::refreshItem($viewerState);
		
		// Returning the viewer state
		return new ViewerStateResource(resource: $refreshedInstance);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(DeleteViewerStateRequest $request, ViewerState $viewerState)
	{
		/**
		 * Getting the authenticated user
		 * @var \App\Models\User $user
		 */
		$user = Auth::guard(name: 'authenticator')->user();

		// Authorizing the request
		$this->authorizeForUser(user: $user, ability: 'delete', arguments: [$viewerState]);

		// Determining if all the versions of the viewer state should be deleted.
		if ($request->validated('deleteAll')) {
			// Deleting all the versions of the viewer state.
			$deletedStates = ViewerStateActions::deleteAllVersions(viewerState: $viewerState, issueEvent: false);
			// Returning the deleted states.
			return new ViewerStateCollection(resource: $deletedStates);
		}

		// Deleting the viewer state.
		$deletedState = ViewerStateActions::deleteVersion(viewerState: $viewerState, issueEvent: false);

		// Returning a response with the amount of deleted versions.
		return new ViewerStateResource(resource: $deletedState);
	}
}
