<?php

namespace App\Source\SharedResources\Domain;

/**
 * An agnostic representation of the entities that can be shared.
 *
 * @property App\Source\SharedResources\Domain\ESharedResourceConnection $connection The connection case that was used for getting the stdClass.
 * @property string $resourceId The primary key of the resource.
 * @property bool $isPublic Whether the resource is public or not.
 */
class SharedResource
{
	use TSharedResource;

	public $connection;

	public $resourceId;

	private $isPublic;

	public function __construct(
		ESharedResourceConnection $connection,
		string $resourceId,
		bool $isPublic = false
	){
		$this->connection = $connection;
		$this->resourceId = $resourceId;
		$this->isPublic = $isPublic;
	}
}