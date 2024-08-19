<?php

use App\Http\Controllers\Api\v1\Authentication\ApiV1AuthenticationController;
use App\Http\Controllers\Api\v1\Authorization\ApiV1AuthorizationController;
use App\Http\Controllers\Api\v1\PublicResources\ApiV1PublicResourcesController;
use App\Http\Controllers\Api\v1\ViewerState\ApiV1ViewerStateController;
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

Route::name('api.public.')
	->group(function () {
		Route::get(uri: 'public-resources', action: [ApiV1PublicResourcesController::class, 'show']);
	});

Route::name('api.')
	->middleware(['authenticator.session'])
	->group(function () {
		Route::apiResource(name: 'authorizations', controller: ApiV1AuthorizationController::class);
		Route::apiResource(name: 'viewer-states', controller: ApiV1ViewerStateController::class);
	});

Route::name('api.auth.')
	->middleware(['authenticator.session'])
	->prefix('auth')
	->group(function () {
		Route::get(uri: 'logout', action: [ApiV1AuthenticationController::class, 'signout'])->name('logout');
	});
