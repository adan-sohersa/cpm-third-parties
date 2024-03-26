<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
	/**
	 * The names of the cookies that should not be encrypted.
	 *
	 * @var array<int, string>
	 */
	protected $except = [
		'next-auth.callback-url',
		'next-auth.csrf-token',
		'next-auth.redirect-after-auth',
		'next-auth.session-token',
		'next-auth.state',
		'next-auth_session-token'
	];
}
