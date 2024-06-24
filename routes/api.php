<?php

use App\Http\Controllers\Api\v1\Authentication\ApiV1AuthenticationController;
use App\Http\Controllers\Api\v1\Authorization\ApiV1AuthorizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::name('api.')
	->middleware(['authenticator.session'])
	->group(function () {
		Route::apiResource(name: 'authorizations', controller: ApiV1AuthorizationController::class);
	});

Route::name('api.auth.')
	->middleware(['authenticator.session'])
	->prefix('auth')
	->group(function () {
		Route::get(uri: 'logout', action: [ApiV1AuthenticationController::class, 'signout'])->name('logout');
	});
