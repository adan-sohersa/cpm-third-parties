<?php

namespace App\Console\Commands;

use App\Enums\Authorization\ThirdPartyProviders;
use Illuminate\Console\Command;
use App\Models\Authorization;
use App\Source\Authorizations\Infraestructure\AutodeskTokenRefresher;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class RefreshToken extends Command
{
	protected $signature = 'app:refresh-token';
	protected $description = 'Command description';

	public function handle()
	{
		$nextHour = Carbon::now()->addHour();
		$authorizations = Authorization::where('expires_at', '<', $nextHour->timestamp)->get();

		foreach ($authorizations as $authorization) {

			if($authorization->provider === ThirdPartyProviders::acc) {
				$this->info('refreshing....');
				$refresher = new AutodeskTokenRefresher();
				$refresher->refreshToken($authorization);
				$this->info('success...');
				return;
			}

		}
	}
}
