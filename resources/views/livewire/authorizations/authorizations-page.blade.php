<section>
	{{-- The Master doesn't talk, he acts. --}}

	<h1>Authorizables: <span>{{$authorizable_type_value}} => {{$authorizable_id}}</span></h1>

	<ul>
		@foreach ($authorizations as $authorization)
				<li wire:key="{{$authorization->id}}">
					<img src={{$authorization->user_picture}} alt={{$authorization->username_at_provider}}>
					{{$authorization->provider}} - {{$authorization->username_at_provider}}
					<button type="button" wire:click="deleteAuthorization('{{ $authorization->id }}')">Eliminar</button>
				</li>
		@endforeach
	</ul>

	<a href={{ $apsAuthorizationUrl }}>New Autodesk Authorization</a>

</section>