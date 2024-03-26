<?php

use App\Http\Controllers\Authorization\AutodeskAuthorizationController;
use App\Livewire\Authorizations\AuthorizationsPage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});

Route::name('authorizations.')
	->group(function () {

		Route::get(uri: '/authorizations', action: AuthorizationsPage::class)
			->name('all');

		Route::get(uri: '/providers/autodesk/callback', action: [AutodeskAuthorizationController::class, 'index'])
			->name(name: 'aps.callback');
	})
	->middleware(['authenticator.session']);
