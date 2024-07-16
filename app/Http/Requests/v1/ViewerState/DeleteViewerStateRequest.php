<?php

namespace App\Http\Requests\v1\ViewerState;

use Illuminate\Foundation\Http\FormRequest;

class DeleteViewerStateRequest extends FormRequest
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
			'deleteAll' => [
				'boolean'
			]
		];
	}

	/**
	 * Prepare inputs for validation.
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$this->merge([
			'deleteAll' => $this->toBoolean($this->deleteAll),
		]);
	}

	/**
	 * Convert to boolean
	 *
	 * @param $booleable
	 * @return boolean
	 */
	private function toBoolean($booleable)
	{
		return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}
}
