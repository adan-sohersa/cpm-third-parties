interface ResourceResponse<T> {
	data: T
}

interface ResourceCollectionResponse<T> {
	data: T[]
}

interface ErrorResponse {
	error: string
}

interface ValidationErrorResponse {
	message: string
	errors: Record<string, string[]>
}

export type {
	ResourceResponse,
	ResourceCollectionResponse,
	ErrorResponse,
	ValidationErrorResponse
};