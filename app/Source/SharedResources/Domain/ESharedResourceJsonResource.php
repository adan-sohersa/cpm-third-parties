<?php

namespace App\Source\SharedResources\Domain;

use App\Enums\EnumToArray;

/**
 * The enum of the different JsonResources that can be used to get
 * a json representation of the corresponding shared resource.
 *
 * Each case name should appear in the ESharedResourceType enum.
 */
enum ESharedResourceJsonResource: string
{
	use EnumToArray;

	/**
	 * The JsonResource class name for the \App\Models\ViewerState model.
	 */
	case VIEWER_STATE = '\App\Http\Resources\Api\v1\ViewerStateResource';
}