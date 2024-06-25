<?php

namespace App\Enums\Authorization;

use App\Enums\EnumToArray;

enum AuthorizableTableWithConnection: string
{
	use EnumToArray;

	/**
	 * The name of the column where the identifier for the corrresponding table is stored.
	 *
	 * @var array<string, string>
	 */
	const IDENTIFIER_PER_TABLE = array(
		'projects_app_db.projects' => 'id',
		'authentication_app_db.User' => 'id'
	);

	case projects = 'projects_app_db.projects';

	case users = 'authentication_app_db.User';
}
