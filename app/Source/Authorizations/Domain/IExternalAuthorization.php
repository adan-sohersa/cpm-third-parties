<?php

namespace App\Source\Authorizations\Domain;

use App\Enums\Authorization\ThirdPartyAuthorizables;
use App\Models\Authorization;

interface IExternalAuthorization
{
	public function convertToInternal(ThirdPartyAuthorizables $authorizableType, string $authorizableId): Authorization;
}