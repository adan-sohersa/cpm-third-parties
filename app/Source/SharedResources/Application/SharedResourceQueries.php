<?php

namespace App\Source\SharedResources\Application;

use App\Source\SharedResources\Domain\ESharedResourceConnection;
use App\Source\SharedResources\Domain\ESharedResourceModel;
use App\Source\SharedResources\Domain\ESharedResourceJsonResource;
use App\Source\SharedResources\Domain\ESharedResourceType;
use App\Source\SharedResources\Domain\SharedResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use stdClass;

class SharedResourceQueries
{

	/**
	 * Determines if the given resource is publicly accessible.
	 *
	 * @param SharedResource $resource The resource to check.
	 * @param boolean $reasonInException Whether to throw an exception if the resource is not publicly accessible.
	 * @return boolean True if the resource is publicly accessible, false otherwise.
	 */
	public static function canBePubliclyAccessed(SharedResource $resource, bool $reasonInException = false): bool
	{
		// Throwing the corresponding exception if required.
		if (!$resource->isPublic() && $reasonInException) {
			throw new \Exception('The resource is not publicly accessible.');
		}

		return $resource->isPublic();
	}

	/**
	 * Resolves the connection case which corresponds to the given type.
	 *
	 * @param string $type The type of the resource.
	 * @return ESharedResourceConnection The connection case.
	 */
	public static function resolveConnectionCase(string $type): ESharedResourceConnection
	{
		// Getting the case of the type.
		$shareableType = ESharedResourceType::tryFrom($type);

		// Throwing the corresponding exception if required.
		if (is_null($shareableType)) {
			throw new \Exception('The type is not valid.');
		}

		// Getting the case of the connection.
		$connectionCase = ESharedResourceConnection::caseFromName(name: $shareableType->name);

		// Throwing the corresponding exception if no connection is found.
		if (is_null($connectionCase)) {
			throw new \Exception('No connection is defined for the given type.');
		}

		// Getting the primary key of the resource.
		$primaryKeyColumn = ESharedResourceConnection::IDENTIFIER_PER_TABLE[$shareableType->name];

		// Throwing an exception it no primary key is found.
		if (is_null($primaryKeyColumn)) {
			throw new \Exception('No primary key is defined for the corresponding connection.');
		}

		// Returning the corresponding case.
		return $connectionCase;
	}

	/**
	 * Returns the raw resource which corresponds to the given type and resource id.
	 *
	 * @param string $type The type of resource to get. This value should be in the ESharedResourceConnection enum.
	 * @param string $resourceId The primary key of the resource to get.
	 * @return stdClass The raw result of the query.
	 */
	public static function getRawSharedResource(string $type, string $resourceId): stdClass
	{
		// Resolving the connection case.
		$connectionCase = self::resolveConnectionCase(type: $type);

		// Getting the corresponding resource.
		$resource = DB::table(table: $connectionCase->value)
			->where(
				column: ESharedResourceConnection::IDENTIFIER_PER_TABLE[$connectionCase->name],
				operator: '=',
				value: $resourceId
			)
			->first();

		// Throwing an exception if no resource is found.
		if (is_null($resource)) {
			throw new \Exception('The resource is not found.');
		}

		// Returning the query result as stdClass.
		return $resource;
	}

	/**
	 * Utilizes the ESharedResourceModel enum to resolve the model class to parse the given resource into an Eloquent model.
	 *
	 * @param string $type The type of the resource to parse. This value should be in the ESharedResourceType enum.
	 * @param stdClass $resource The resource to parse.
	 * @return Model An instance of the corresponding model class.
	 */
	public static function stdClassToEloquentModel(string $type, stdClass $resource): Model
	{
		// Resolving the type case.
		$typeCase = ESharedResourceType::tryFrom($type);

		// Resolving the model class.
		$modelCase = ESharedResourceModel::caseFromName(name: $typeCase->name);

		// Throwing an exception if no model class is found.
		if (is_null($modelCase)) {
			throw new \Exception('No model class is defined for the given type.');
		}

		// Getting the corresponding model class.
		$modelClass = $modelCase->value;

		// Parsing the stdClass to an array.
		$resourceAsArray = json_decode(json_encode($resource), true);

		/**
		 * Getting the corresponding Model instance.
		 * @var \Illuminate\Database\Eloquent\Model $resourceAsModel
		 */
		$resourceAsModel = new $modelClass($resourceAsArray);

		// Setting the complementary attributes.
		$resourceAsModel['created_at'] = $resource->created_at;
		$resourceAsModel['updated_at'] = $resource->updated_at;
		$resourceAsModel['id'] = $resource->id;

		// Resolving the default relations.
		$defaultRelations = ESharedResourceModel::DEFAULT_MODEL_RELATIONS[$modelCase->name];

		// Loading the relations.
		$resourceAsModel->loadMissing(...$defaultRelations);

		// Returning the corresponding model.
		return $resourceAsModel;
	}

	/**
	 * Returns the given stdClass as a JsonResource of the corresponding type.
	 *
	 * @param string $type The type of the resource to parse. This value should be in the ESharedResourceType enum.
	 * @param stdClass $resource The resource to parse.
	 * @return JsonResource The corresponding JsonResource.
	 */
	public static function stdClassToJsonResource(string $type, stdClass $resource): JsonResource
	{
		// Parsing the stdClass to an Eloquent model.
		$resourceAsModel = self::stdClassToEloquentModel(type: $type, resource: $resource);

		// Resolving the corresponding type case.
		$typeCase = ESharedResourceType::tryFrom($type);

		// Resolving the corresponding JSonResource class.
		$jsonResourceCase = ESharedResourceJsonResource::caseFromName(name: $typeCase->name);

		// Throwing an exception if no JSonResource class is found.
		if (is_null($jsonResourceCase)) {
			throw new \Exception('No JSonResource class is defined for the given type.');
		}

		// Getting the corresponding JSonResource class.
		$jsonResourceClass = $jsonResourceCase->value;

		// Returning the corresponding JSonResource.
		return new $jsonResourceClass($resourceAsModel);
	}

	/**
	 * Tries to get the stdClass corresponding to the given type and resource id.
	 * Then, it parses the stdClass into a SharedResource instance.
	 *
	 * @param string $type The type of the resource to parse. This value should be in the ESharedResourceType enum.
	 * @param string $resourceId The primary key of the resource to parse.
	 * @return SharedResource The SharedResource representing the corresponding stdClass.
	 */
	public static function getSharedResource(string $type, string $resourceId): SharedResource
	{
		// Resolving the connection case.
		$connectionCase = self::resolveConnectionCase(type: $type);

		// Getting the corresponding stdClass.
		$resource = self::getRawSharedResource(type: $type, resourceId: $resourceId);

		// Returning the query result as SharedResource.
		return new SharedResource(
			connection: $connectionCase,
			resourceId: $resourceId,
			isPublic: $resource->isPublic ?? $resource->is_public ?? false
		);
	}
}
