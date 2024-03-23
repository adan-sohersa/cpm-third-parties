<?php

namespace App\Livewire\Authorizations;

use App\Enums\Authorization\ThirdPartyAuthorizables;
use App\Models\Authorization;
use App\Source\AutodeskPlatformServices\ApsAuthentication;
use Livewire\Attributes\Url;
use Livewire\Component;

class AuthorizationsPage extends Component
{
	#[Url('type')]
	public string $authorizable_type_value = '';

	#[Url('authorizable')]
	public string $authorizable_id = '';

	private ?ThirdPartyAuthorizables $authorizableType = null;

	public string $apsAuthorizationUrl = '';

	/**
	 * @var \Illuminate\Support\Collection
	 */
	public $authorizations;

	public function boot()
	{
		$this->authorizableType = ThirdPartyAuthorizables::tryFrom($this->authorizable_type_value) ?? ThirdPartyAuthorizables::USER;
		$this->apsAuthorizationUrl = ApsAuthentication::authorizationEndpoint($this->authorizableType, $this->authorizable_id);
		$this->setRecalculatedProperties();
	}
	
	private function setRecalculatedProperties()
	{
		$this->setAuthorizations();
	}
	
	private function setAuthorizations()
	{
		$this->authorizations = Authorization::where('authorizable_class', $this->authorizableType->value)
			->where('authorizable_id', $this->authorizable_id)
			->get();
	}

	public function deleteAuthorization($authorization_id)
	{
		$authorization = $this->authorizations->firstWhere(key: 'id', value: $authorization_id);
		$authorization->delete();
	}

	public function render()
	{
		$this->setRecalculatedProperties();
		return view('livewire.authorizations.authorizations-page');
	}
}
