<?php

namespace App\Http\Requests\v1\PublicResources;

use App\Source\SharedResources\Domain\ESharedResourceConnection;
use App\Source\SharedResources\Domain\ESharedResourceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class GetPublicResourceRequest extends FormRequest
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
	public function rules(): array
	{
		return [
			'type' => [
				'bail',
				'required',
				'string',
				Rule::enum(ESharedResourceType::class)
			],
			'resourceId' => [
				'bail',
				'required',
				'uuid',
			]
		];
	}

	/**
	 * Validates the existence of the corresponding resource.
	 * This is done after the validation to prevent that the
	 * arguments evaluation throws unexpected errors.
	 *
	 * @return void
	 */
	public function after()
	{
		return [
			function (Validator $validator) {
				if (!$validator->failed()) {
					FacadesValidator::make($this->input(), [
						'resourceId' => [
							Rule::exists(
								table: ESharedResourceConnection::caseFromName(
									name: ESharedResourceType::tryFrom(value: $this->type)->name
								)->value,
								column: ESharedResourceConnection::IDENTIFIER_PER_TABLE[ESharedResourceType::tryFrom(value: $this->type)->name]
							)
						]
					])->validate();
				}
			}
		];
	}
}
