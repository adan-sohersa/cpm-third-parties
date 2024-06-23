import { RepositoryModel } from "@/types"

interface Authorization extends RepositoryModel {
	id: string
	provider: string
	token: string
	scopes?: string
	pictureAtProvider?: string
	usernameAtProvider?: string
	active: boolean
}

export { type Authorization }