<?php

namespace App\Http\Controllers\Authorization;

use App\Enums\Authorization\ThirdPartyAuthorizables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authorization\AllAuthorizationsRequest;
use App\Http\Resources\AuthorizationResource;
use App\Models\Authorization;
use App\Source\AutodeskPlatformServices\ApsAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthorizationController extends Controller
{
	public function index(AllAuthorizationsRequest $request)
	{
		// Authorizable Case
		$authorizableCase = isset($request->type)
			? ThirdPartyAuthorizables::from(value: $request->type)
			: ThirdPartyAuthorizables::USER;

		// Authorizable Id
		$authorizableId = $authorizableCase === ThirdPartyAuthorizables::USER
			? Auth::guard(name: 'authenticator')->user()->id
			: $request->authorizable;

		// QUERY
		$authorizations = Authorization::where('authorizable_class', $authorizableCase->value)
			->where('authorizable_id', $authorizableId)
			->get();

		$providersWithAuthorizationURL = [
			'ACC' => ApsAuthentication::authorizationEndpoint(authorizable_type: $authorizableCase, authorizable_id: $authorizableId)
		];

		// disabling the wrapping of the resource collection just for this request
		AuthorizationResource::withoutWrapping();

		// RESPONSE
		return Inertia::render('Authorizations/AllAuthorizationsPage', [
			// Returning the authorizations through the AuthorizationResource collection in order to
			// transform the attributes to the format expected by the frontend
			'authorizations' => AuthorizationResource::collection($authorizations),
			'type' => $authorizableCase->value,
			'authorizable' => $authorizableId,
			'user' => Auth::guard(name: 'authenticator')->user(),
			'providersWithAuthorizationURL' => $providersWithAuthorizationURL
		]);
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
	public function show(Authorization $authorization)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Authorization $authorization)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Authorization $authorization)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Authorization $authorization)
	{
		//
	}

}