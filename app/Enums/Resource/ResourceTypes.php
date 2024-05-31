<?php

namespace App\Enums\Resource;

use App\Enums\EnumToArray;

enum ResourceTypes: string
{
	use EnumToArray;

	/**
	 * This type should be used to attach some project from ACC to a internal project.
	 */
	case accProject = 'ACC_PROJECT';

	/**
	 * This type of resource should be used when the resource is a BIM Model, regardless if it comes from the OSS provided by the APS or from an ACC user's account.
	 */
	case model = 'MODEL';

	/**
	 * This type of resource should be used when the reource is the image to use as the cover of its resourceable.
	 */
	case cover = 'COVER';

	/**
	 * This type of resource should be used when the reource is a regular image for the resourceable.
	 */
	case image = 'IMAGE';

	/**
	 * This type of resource should be used for any other kind of file not listaed her.
	 */
	case file = 'FILE';
}
