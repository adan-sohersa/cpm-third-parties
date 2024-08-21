<?php

namespace App\Source\SharedResources\Domain;

use App\Enums\EnumToArray;

/**
 * The enum of the different tables where the sharable resources are stored.
 * The value of the cases represents the table name of the corresponding
 * entity in the following way: `${dbConnection}.${tableName}`.
 * 
 * Each case name should appear in the ESharedResourceType enum.
 */
enum ESharedResourceConnection: string
{
	use EnumToArray;

	/**
	 * The primary key of the corresponding table.
	 * Each key of the array should correspond to a case name. 
	 */
	const IDENTIFIER_PER_TABLE = array(
		'VIEWER_STATE' => 'id'
	);

	/**
	 * The case for the \App\Models\ViewerState model.
	 */
	case VIEWER_STATE = 'viewer_states';
}