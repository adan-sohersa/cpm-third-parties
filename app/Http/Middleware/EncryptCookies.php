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
		'next-auth_callback-url',
		'next-auth_csrf-token',
		'next-auth_redirect-after-auth',
		'next-auth_session-token',
		'next-auth_state',
		'__Secure-next-auth.callback-url',
		'__Secure-next-auth.csrf-token',
		'__Secure-next-auth.redirect-after-auth',
		'__Secure-next-auth.session-token',
		'__Secure-next-auth.state',
		'__Secure-next-auth_callback-url',
		'__Secure-next-auth_csrf-token',
		'__Secure-next-auth_redirect-after-auth',
		'__Secure-next-auth_session-token',
		'__Secure-next-auth_state',
	];
}
