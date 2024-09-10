<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\AuthorizationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewerStateResource extends JsonResource
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
			'name' => $this->name,
			'version' => $this->version,
			'authorizationId' => $this->authorization_id,
			'isPublic' => $this->is_public,
			'state' => $this->when(
				condition: !$request->resumed,
				value: json_decode(json: $this->state, associative: true)
			),
			'authorization' => new AuthorizationResource(resource: $this->whenLoaded('authorization')),
			'created_at' => $this->created_at,
		];
	}
}
