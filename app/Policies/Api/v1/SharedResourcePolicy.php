<?php

namespace App\Policies\Api\v1;

use App\Models\User;
use App\Source\SharedResources\Application\SharedResourceQueries;
use App\Source\SharedResources\Domain\SharedResource;
use Illuminate\Auth\Access\Response;

/**
 * Defines the policies for entities that are handled as `SharedResource`s.
 */
class SharedResourcePolicy
{

	/**
	 * Determine whether a SharedResource can be accessed publicly or by a user.
	 *
	 * @param User|null $user The user that is trying to access the resource.
	 * @param SharedResource $resource The resource that is being accessed.
	 * @return Response The response of the policy.
	 */
	public function view(?User $user, SharedResource $resource): Response
	{
		try {
			if (
				SharedResourceQueries::canBePubliclyAccessed(resource: $resource, reasonInException: true)
			) {  
				return Response::allow();
			}
		} catch (\Throwable $th) {
			return Response::deny($th->getMessage());
		}

	}
}
