<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Authorization;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class RefreshToken extends Command
{
	protected $signature = 'app:refresh-token';
	protected $description = 'Command description';

	private function getNewAccessToken($refreshToken)
	{
		$clientID = env('APS_CLIENT_ID');
		$clientSecret = env('APS_CLIENT_SECRET');
		$body = [
			'grant_type' => 'refresh_token',
			'refresh_token' => $refreshToken,
		];
		$response = Http::withBasicAuth($clientID, $clientSecret)->withHeaders([
			'Content-Type' => 'application/x-www-form-urlencoded'
		])->asForm()->post('https://developer.api.autodesk.com/authentication/v2/token', $body);
		if ($response->successful()) {
			$authorization = Authorization::where('refresh_token', $refreshToken)->first();
			$data = $response->json();
			$newAccessToken = $data['access_token'];
			if ($authorization) {
				$authorization->access_token = $newAccessToken;
				$authorization->save();
				$this->info("Token actualizado correctamente en la base de datos.");
		}else {
			$this->error("No se encontró el registro de autorización correspondiente.");
	} 
			$this->info("El token fue reasignado conexito, el nuevo token es: $newAccessToken");
		} else {
			$this->error('No se logro la communicacion, response: ' . json_encode($response->json()));
		}
	}

	public function handle()
	{
		$authorizations = Authorization::all(['expires_at', 'refresh_token']);
		foreach ($authorizations as $authorization) {
			$refreshToken = $authorization->refresh_token;
			$expiresAt = Carbon::createFromTimestamp($authorization->expires_at);
			$this->info('El token original expira en: ' . $expiresAt . 'El refresh_token es: ' . $refreshToken);
			if ($expiresAt->isPast()) {
				echo 'La fecha de expiracion ya ha pasado';
				$newAccessToken = $this->getNewAccessToken($refreshToken);
				$this->info($newAccessToken);
			} else {
				$nextHour = Carbon::now()->addHour();
				if ($expiresAt->isBefore($nextHour)) {
					echo 'Los tokens expiran la proxma hora';
					$newAccessToken = $this->getNewAccessToken($refreshToken);
				}
			}
		}
	}
}
