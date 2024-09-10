import { mergeInstanceWithResponse } from "@/src/general/application/update-instance-with-response";
import { Authorization } from "../domain";

/**
 * Calls the corresponding endpoint to refresh the
 * authorization's token and returns the result
 * of merging the api response with the given
 * authorization without modifying it.
 * 
 * @param authorization The authorization to refresh without modifying it.
 * @returns The merge of the api response with the given authorization.
 */
const refreshToken = (authorization: Authorization): Promise<Authorization> => {

	return fetch(`/api/authorizations/${authorization.id}`, {
		method: 'PUT',
		headers: {
			'Accept': 'application/json'
		}
	})
		.then(response => mergeInstanceWithResponse(authorization, response))
}

export { refreshToken };