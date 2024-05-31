<?php

namespace App\Enums\Authorization;

use App\Enums\EnumToArray;

enum ThirdPartyProviders: string
{
	use EnumToArray;

	case acc = 'ACC';
	case google = 'GOOGLE';
}