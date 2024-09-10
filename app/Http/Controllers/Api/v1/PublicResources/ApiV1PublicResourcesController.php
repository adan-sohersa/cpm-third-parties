<?php

namespace App\Http\Controllers\Api\v1\PublicResources;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\PublicResources\GetPublicResourceRequest;
use App\Source\SharedResources\Application\SharedResourceQueries;
use App\Source\SharedResources\Domain\SharedResource;

class ApiV1PublicResourcesController extends Controller
{
	/**
	 * Looks for the corresponding resource and returns it as a JSON resource
	 *
	 * @param GetPublicResourceRequest $request The validated request.
	 * @return The corresponding JSON resource.
	 */
	public function show(GetPublicResourceRequest $request)
	{

		// Getting the corresponding resource
		$sharedResource = SharedResourceQueries::getSharedResource(
			type: $request->validated('type'),
			resourceId: $request->validated('resourceId')
		);

		// Authorizing the request
		$this->authorizeForUser(
			user: null,
			ability: 'view',
			arguments: [SharedResource::class, $sharedResource]
		);

		// Getting the corresponding instance
		$resolvedResource = SharedResourceQueries::getRawSharedResource(
			type: $request->validated('type'),
			resourceId: $request->validated('resourceId')
		);

		// Returning the resource
		return SharedResourceQueries::stdClassToJsonResource(
			type: $request->validated('type'),
			resource: $resolvedResource
		);
	}
}
