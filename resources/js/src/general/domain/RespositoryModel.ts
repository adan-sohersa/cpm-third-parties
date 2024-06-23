interface RepositoryModel {
	id?: string | number
	created_at?: string
	updated_at?: string
	deleted_at?: string

	errors?: Record<string, string[]>
}

export { type RepositoryModel }