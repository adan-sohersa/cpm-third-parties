<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Authorization;
use Illuminate\Console\Command;
use App\Source\Authorizations\Application\RefreshIAuthorization;

class RefreshTokensCommand extends Command
{
	protected $signature = 'authorizations:refresh-tokens';
	protected $description = 'This command will look for authorizations that have expired and refresh them.';

	public function handle()
	{
		// Create a reference to the next hour
		$nextHour = Carbon::now()->addHour();
		// Get all authorizations that will expire in the next hour
		$authorizations = Authorization::where('expires_at', '<=', $nextHour->timestamp)->get();

		// Loop through each authorization that will expire in the next hour
		foreach ($authorizations as $authorization) {
			// Calling the domain action to refresh the IAuthorization
			RefreshIAuthorization::refreshToken($authorization);
		}
	}
}
