<?php

namespace App\Source\SharedResources\Domain;

/**
 * The enum of the different types of shared resources.
 * The case name is the name of all the enum cases which works based on the resource type.
 * While, the case value corresponds to a URL-compatible alias of the resource type.
 */
enum ESharedResourceType: string
{

	/**
	 * The case for the \App\Models\ViewerState model.
	 */
	case VIEWER_STATE = 'viewer-state';

}