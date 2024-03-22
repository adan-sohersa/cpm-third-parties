<?php

namespace App\Enums\Authorization;

enum ThirdPartyAuthorizables: string
{
	case PROJECT = 'projects';
	case USER = 'users';
}
