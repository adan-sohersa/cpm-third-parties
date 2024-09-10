<?php

namespace App\Source\SharedResources\Domain;

/**
 * The common methods that are implemented in the entities that can be shared.
 */
trait TSharedResource
{

	/**
	 * Determines whether the resource is public or not.
	 *
	 * @return boolean
	 */
	public function isPublic(): bool {
		return $this->is_public ?? $this->isPublic ?? false;
	}

}
