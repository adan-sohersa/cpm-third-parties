<?php

namespace App\Http\Requests\Authorization;

use App\Enums\Authorization\ThirdPartyAuthorizables;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AllAuthorizationsRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
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
				Rule::in(ThirdPartyAuthorizables::values())
			],
			'authorizable' => [
				'uuid',
				// Rule::exists(
				// 	table: AuthorizableConnection::fromName(name: $request->type),
				// 	column: AuthorizableIdentifier::fromName(name: $request->type)
				// )
			]
		];
	}

	/**
	 * Prepare the data for validation.
	 */
	protected function prepareForValidation(): void
	{
		$this->merge([
			'auhtorizable_class' => $this->authorizableType,
			'authorizable_id' => $this->authorizableId
		]);
	}
}
