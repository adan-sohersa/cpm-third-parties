<?php

namespace App\Source\Authorizations\Domain;

/**
 * This interface defines the database operations related with authorizations. These methods must be implemented by authorization repository.
 */
interface IAuthorizationRepository
{
	/**
	 * Update the model in the database.
	 *
	 * @param  array  $attributes
	 * @param  array  $options
	 * @return bool
	 */
	public function update(array $attributes = [], array $options = []);
}
