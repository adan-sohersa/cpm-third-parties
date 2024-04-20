<?php
namespace App\Http\Controllers\Authorization;

use App\Enums\Authorization\AuthorizableConnection;
use App\Enums\Authorization\AuthorizableIdentifier;
use App\Enums\Authorization\AuthorizableTable;
use App\Enums\Authorization\ThirdPartyAuthorizables;
use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Source\AutodeskPlatformServices\ApsAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AuthorizationController extends Controller
{
	# Pasando la base de datos de 'projects-wt-next' para su verificacion, en teoria
	public function verifyProjects()
    {
			  $proyectos = DB::table('projects-wt-next')->get();
        return view('authorizations-page', compact('proyectos'));
    }

	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		// REQUEST VALIDATION
		if ($requestErrors = AuthorizationController::validateFiltersInRequest(request: $request)) {
			return response()->json(data: $requestErrors, status: 422);
		};
	}

	public function all(Request $request)
	{

		// REQUEST VALIDATION
		if ($requestErrors = AuthorizationController::validateFiltersInRequest(request: $request)) {
			return response()->json(data: $requestErrors, status: 422);
		};

		// Resolve type
		$authorizableCase = ThirdPartyAuthorizables::from(value: $request->type);

		// QUERY
		$authorizations = Authorization::where('authorizable_class', $authorizableCase->value)
			->where('authorizable_id', $request->authorizable)
			->get();

		$apsAuthorizationUrl = ApsAuthentication::authorizationEndpoint(authorizable_type: $authorizableCase, authorizable_id: $request->authorizable);

		// RESPONSE
		return Inertia::render('Authorizations/AllAuthorizationsPage', [
			'authorizations' => $authorizations,
			'type' => $authorizableCase->value,
			'authorizable' => $request->authorizable,
			'user' => Auth::guard(name: 'authenticator')->user(),
			'apsAuthorizationUrl' => $apsAuthorizationUrl,
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

	/**
	 * Validates the params of the request in order to determine if it is usefull for the operation of the controller.
	 *
	 * @param Illuminate\Http\Request $request The request to validate.
	 * @return \Illuminate\Support\MessageBag|null The validator errors in case of.
	 */
	public static function validateFiltersInRequest(Request $request)
	{
		$signaturesValidator = Validator::make(
			data: $request->all(),
			rules: [
				'type' => [
					Rule::in(ThirdPartyAuthorizables::values()),
					'required'
				],
				'authorizable' => [
					'uuid',
					'required'
				]
			]
		);

		if ($signaturesValidator->fails()) {
			return $signaturesValidator->errors();
		}

	//	return;

		$existenceValidator = Validator::make(
			data: $request->all(),
			rules: [
				'authorizable' => Rule::exists(
					table: AuthorizableConnection::fromName(name: $request->type) . "." . AuthorizableTable::fromName(name: $request->type),
					column: AuthorizableIdentifier::fromName(name: $request->type)
				)
			]
		);

		if ($existenceValidator->fails()) {
			return $existenceValidator->errors();
		}
	}
}
