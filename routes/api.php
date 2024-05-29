<?php

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

/**
 * @todo Protect the route with the authentication middleware.
 */
Route::apiResource(name: 'authorizations', controller: ApiV1AuthorizationController::class);
