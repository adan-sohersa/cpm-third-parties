import { EloquentModel } from "./eloquent-model"

export interface Authorization extends EloquentModel {

	id: string
	provider: string
	token: string
	scopes?: string
	pictureAtProvider?: string
	usernameAtProvider?: string
	active: boolean
}