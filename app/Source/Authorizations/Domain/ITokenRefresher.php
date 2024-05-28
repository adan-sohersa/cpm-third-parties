<?php

namespace App\Source\AutodeskPlatformServices\Domain;

use App\Models\Authorization;

interface ITokenRefresher
{
    public function refreshToken(Authorization $authorization);
}