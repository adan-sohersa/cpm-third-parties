<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorizationResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'provider' => $this->provider,
			'token' => $this->access_token,
			'scopes' => $this->scopes,
			'expiresAt' => $this->expires_at,
			'active' =>  $this->isTokenActive,
			'usernameAtProvider' => $this->username_at_provider,
			'pictureAtProvider' => $this->user_picture,
			'authorizableId' => $this->authorizable_id,
			'authorizableType' => $this->authorizable_class
		];
	}
}
