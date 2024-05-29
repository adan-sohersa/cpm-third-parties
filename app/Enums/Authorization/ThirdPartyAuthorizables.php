<?php

namespace App\Enums\Authorization;

use App\Enums\EnumToArray;

enum ThirdPartyAuthorizables: string
{
	use EnumToArray;

	case PROJECT = 'projects';
	case USER = 'users';
}

enum AuthorizableConnection: string
{
	use EnumToArray;

	case projects = 'projects_app_db';

	case users = 'authentication_app_db';
}

enum AuthorizableIdentifier: string 
{
	use EnumToArray;

	case projects = 'id';
}