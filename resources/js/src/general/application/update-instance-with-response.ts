import { RepositoryModel } from "../domain";
import { ValidationErrorResponse } from "../domain/ApiResponses";

/**
 * Validates the given response and merges it with the given instance, returning the result without overwriting the given instance.
 * @param instance The RepositoryModel instance to use as base for the merge.
 * @param response Te Response object to merge with the instance.	
 * @returns The result of merging the given instance with the response.
 */
const mergeInstanceWithResponse = async <T extends RepositoryModel>(instance: T, response: Response): Promise<T> => {

	// Turning the response into a JSON object
	const jsonResponse = await response.json()

	// Hanlding the successful responses
	if (response.ok) {

		// Throwing an error if the data property is not an object
		if (typeof jsonResponse !== 'object' || typeof jsonResponse.data !== "object") {
			throw new Error(`Invalid data in response. An object was expected, ${typeof jsonResponse.data} was received instead.`)
		}

		// Throwing an error if the data property is an array
		if (Array.isArray(jsonResponse.data)) {
			throw new Error("Invalid data in response. An object was expected, an array was received instead.")
		}

		// Returning the merge of the data property with the given instance
		return { ...instance, ...jsonResponse.data }

	}

	// Handling the validation errors with 422 status code
	if (response.status === 422) {
		// Returning the merge of the errors property with the given instance
		return { ...instance, errors: (jsonResponse as ValidationErrorResponse).errors };
	}

	// Handling the general ErrorResponse
	if (typeof jsonResponse === 'object' && typeof jsonResponse.error !== "undefined") {
		throw new Error(jsonResponse.error);
	}

	// Throwing all the anonymous errors
	throw new Error(response.statusText);

}

export { mergeInstanceWithResponse }