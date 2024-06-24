<?php

namespace App\Http\Controllers\Authorization;

use App\Enums\Authorization\ThirdPartyAuthorizables;
use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Source\AutodeskPlatformServices\ApsAuthentication;
use App\Source\AutodeskPlatformServices\ApsToken;
use Exception;
use Illuminate\Http\Request;

class AutodeskAuthorizationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		if (!$request->has('code') || empty($request->code)) {
			throw new Exception('No authorization code was provided by the provider');
		}

		if (!$request->has('state') || empty($request->state)) {
			throw new Exception('No state was provided');
		}

		// APS_TOKEN
		// Code
		$tokenResponse = ApsAuthentication::getTokenByAuthorizationCode($request->code);
		// Instance
		$apsAuthorization = new ApsToken(
			$tokenResponse->json()
		);

		// AUTHORIZATION
		// State
		$requestState = json_decode(urldecode($request->state));
		$authorizable_type = ThirdPartyAuthorizables::from($requestState->authorizable_type);
		$authorizable_id = $requestState->authorizable_id;
		// Virtual instance
		$authorizationArray = $apsAuthorization
			->convertToInternal(authorizableType: $authorizable_type, authorizableId: $authorizable_id)
			->toArray();
		// Verification fields
		$fieldsToVerify = array_filter(
			$authorizationArray,
			fn ($key) => in_array(needle: $key, haystack: Authorization::UNIQUE_KEYS),
			ARRAY_FILTER_USE_KEY
		);

		// Persisted instance
		Authorization::updateOrCreate(
			$fieldsToVerify,
			$authorizationArray
		);

		return redirect()
			->route(
				route: 'authorizations.all',
				parameters: [
					'type' => $authorizable_type,
					'authorizable' => $authorizable_id
				]
			);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		//
	}
}
