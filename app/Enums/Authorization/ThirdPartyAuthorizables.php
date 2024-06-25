<?php

namespace App\Enums\Authorization;

use App\Enums\EnumToArray;

enum ThirdPartyAuthorizables: string
{
	use EnumToArray;

	case PROJECT = 'projects';

	case USER = 'users';

}
