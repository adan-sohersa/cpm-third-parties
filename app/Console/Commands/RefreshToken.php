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

	private function getNewAccessToken(Authorization $authorization){
//	private function getNewAccessToken($refreshToken){

		// Se toman los datos de la cuenta de Autodesk
		$clientID = env('APS_CLIENT_ID');
		$clientSecret = env('APS_CLIENT_SECRET');

		// Se construye el cuerpo de la solicitud
		$body = [
			'grant_type' => 'refresh_token',
			'refresh_token' => $authorization->refresh_token,
		];

		// Se realiza la solicitu POST
		$response = Http::withBasicAuth($clientID, $clientSecret)->withHeaders([
			'Content-Type' => 'application/x-www-form-urlencoded'
		])->asForm()->post('https://developer.api.autodesk.com/authentication/v2/token', $body);


		if ($response->successful()) {
			$data = $response->json();
			$newAccessToken = $data['access_token'];
			$authorization->access_token = $newAccessToken;
			// Authorization::where('refresh_token', $refreshToken)->update(['access_token' => $newAccessToken]);
			$this->info("Token actualizado correctamente en la base de datos");

		} else {
			$this->error('No se logro la communicacion, response: ' . json_encode($response->json()));
		}
	}

	public function handle()
	{
		$nextHour = Carbon::now()->addHour();
		$authorizations = Authorization::where('expires_at', '<', $nextHour->timestamp)->get();

		foreach ($authorizations as $authorization) {
	//	$refreshToken = $authorization->refresh_token;
			$expiresAt = Carbon::createFromTimestamp($authorization->expires_at);
			$this->info('El token original expira en: ' . $expiresAt . 'El refresh_token es: ' . $authorization->access_token);
			$this->getNewAccessToken($authorization);
		}
	}
}
