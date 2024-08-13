<?php

namespace App\Http\Requests\v1\ViewerState;

use App\Source\ViewerStates\Application\ViewerStateQueries;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class UpdateViewerStateRequest extends FormRequest
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
				'bail',
				'string',
				'between:3,255',
				function (string $attribute, mixed $value, Closure $fail) {
					// Determining if the given name has already been taken by another state related to the same authorizable entity.
					if (!ViewerStateQueries::isTheNameAvailable($this->viewerState, $value)) {
						$fail("The name \"{$value}\" has already been taken by another state.");
					}
				},
			],
			'is_public' => 'boolean'
		];
	}

	/**
	 * Prepare inputs for validation.
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		if ($this->has(key: 'isPublic')) {
			$this->merge([
				'is_public' => $this->toBoolean($this->isPublic),
			]);
		}
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
