<?php

namespace App\Enums\Authorization;


enum ThirdPartyAuthorizables: string
{

	case PROJECT = 'projects';
	case USER = 'users';
}

enum AuthorizableConnection: string
{

	case projects = 'projects_app_db';

	case users = 'authentication_app_db';
}

enum AuthorizableIdentifier: string 
{

	case projects = 'id';
}