<?php

namespace App\Source\SharedResources\Domain;

use App\Enums\EnumToArray;

/**
 * The enum of the different eloquent models that can be interpreted as shared resources.
 * 
 * Each case name should appear in the ESharedResourceType enum.
 */
enum ESharedResourceModel: string
{
	use EnumToArray;

	/**
	 * The default relations that should be loaded when parsing the corresponding model.
	 * Each key in the array should correspond to a case name. 
	 */
	const DEFAULT_MODEL_RELATIONS = [
		'VIEWER_STATE' => [
			'authorization'
		]
	];

	/**
	 * The eloquent-model class name for the \App\Models\ViewerState model.
	 */
	case VIEWER_STATE = '\App\Models\ViewerState';
}