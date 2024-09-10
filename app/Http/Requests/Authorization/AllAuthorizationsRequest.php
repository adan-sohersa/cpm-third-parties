<?php

namespace App\Http\Requests\Authorization;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Authorization\ThirdPartyAuthorizables;
use App\Enums\Authorization\AuthorizableTableWithConnection;
use Illuminate\Support\Facades\Auth;

class AllAuthorizationsRequest extends FormRequest
{

	/**
	 * The case of the authorizable in the ThirdPartyAuthorizables enum.
	 *
	 * @var ThirdPartyAuthorizables
	 */
	private ThirdPartyAuthorizables $authorizableCase;

	/**
	 * The connection to use for validating the existence of the authorizable.
	 *
	 * @var AuthorizableTableWithConnection
	 */
	private AuthorizableTableWithConnection $tableWithConnection;

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(Request $request): bool
	{
		// Determine the authorizable case
		$this->authorizableCase = isset($request->type) && !is_null(ThirdPartyAuthorizables::tryFrom(value: $request->type))
			? ThirdPartyAuthorizables::from(value: $request->type)
			: ThirdPartyAuthorizables::USER;

		// Determine the connection to use for validating the existence of the authorizable
		$this->tableWithConnection = AuthorizableTableWithConnection::caseFromName(name: $this->authorizableCase->value);

		// Handle the authorization when the authorizable is an user.
		if ($this->authorizableCase === ThirdPartyAuthorizables::USER) {

			// Allow the request if the authorizable is unset.
			if (!isset($request->authorizable)) {
				return true;
			}

			// Allow the request if the authorizable is equals to the authenticated user id.
			return $request->authorizable === Auth::guard(name: 'authenticator')->user()->id;
		}

		// Denny the request when the authorizable is not an user.
		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(Request $request): array
	{
		return [
			'type' => [
				Rule::enum(ThirdPartyAuthorizables::class)
			],
			'authorizable' => [
				'bail',
				Rule::requiredIf($this->authorizableCase !== ThirdPartyAuthorizables::USER),
				'uuid',
				Rule::exists(
					table: $this->tableWithConnection->value,
					column: AuthorizableTableWithConnection::IDENTIFIER_PER_TABLE[$this->tableWithConnection->value]
				)
			]
		];
	}
}
