<?php

namespace App\Http\Controllers\Api\v1\Authorization;

use App\Enums\Authorization\ThirdPartyAuthorizables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authorization\AllAuthorizationsRequest;
use App\Http\Resources\AuthorizationCollection;
use App\Http\Resources\AuthorizationResource;
use App\Models\Authorization;
use App\Source\Authorizations\Application\RefreshIAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiV1AuthorizationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
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

		// RESPONSE
		return new AuthorizationCollection($authorizations);
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
		return new AuthorizationResource($authorization);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Authorization $authorization)
	{
		try {
			$authorization = RefreshIAuthorization::refreshToken($authorization);
			return new AuthorizationResource($authorization);
		} catch (\Throwable $th) {
			return response()->json([
				'error' => $th->getMessage()
			], 500);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Authorization $authorization)
	{
		//
	}
}
