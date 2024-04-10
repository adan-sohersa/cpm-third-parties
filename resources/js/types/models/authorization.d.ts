import { EloquentModel } from "./eloquent-model"

export interface Authorization extends EloquentModel {

	id: string
	provider: string
	access_token: string
	scopes?: string
	user_picture?: string
	username_at_provider?: string

}