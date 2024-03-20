<?php

namespace App\Enums\Authorization;

enum ThirdPartyProviders: string
{
	case acc = 'ACC';
	case google = 'GOOGLE';
}