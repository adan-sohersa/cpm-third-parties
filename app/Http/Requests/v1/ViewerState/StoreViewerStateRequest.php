<?php

namespace App\Http\Requests\v1\ViewerState;

use Illuminate\Foundation\Http\FormRequest;

class StoreViewerStateRequest extends FormRequest
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
			'name' => [
				'required',
				'string',
				'between:3,255',
			],
			'authorization_id' => [
				'bail',
				'required',
				'uuid',
				'exists:authorizations,id',
			],
			'state' => [
				'required',
				'json'
			]
		];
	}

	/**
	 * Prepare the data for validation.
	 */
	protected function prepareForValidation(): void
	{
		$this->merge([
			'authorization_id' => $this->authorizationId
		]);
	}
}
