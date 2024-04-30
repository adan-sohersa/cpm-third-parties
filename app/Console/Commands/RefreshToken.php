<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Authorization;
use Illuminate\Support\Facades\Http;

class RefreshToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta la expiración de los tokens de ACC y actualiza los que expirarán en la próxima hora';

    /**
     * Execute the console command.
     */

		 private function getNewAccessToken($refreshToken)
		 {

			//Aquí crearé un array 'asociativo' con los valores de client_id, client_secret, grant_type
			//y refresh_token, que son necesarios para autenticarse en la api de autodesk pero realmente
			//no sé si siempre sean necesarios puesto que en teoría ya estaba iniciada la sesión
				 $response = Http::post('https://developer.api.autodesk.com/authentication/v2/token', [
						 'client_id' => env('APS_CLIENT_ID'),
						 'client_secret' => env('APS_CLIENT_SECRET'),
						 'grant_type' => 'refresh_token',
						 'refresh_token' => $refreshToken,
				 ]);
				
				 // Verificamos si se tiene una respuesta exitosa, sino, debemos manejar los errores pero a ver cómo
				 if ($response->successful()) {
						 $data = $response->json();
						 return $data['access_token'];
				 } else {
						 // Aquí se manejarán los errores (por ejemplo, token no válido, etc.)
						 return null;
				 }
		}

    public function handle()
    {
			$now = Carbon::now();
			$nextHour = $now->copy()->addHour();
			$this->info("Hora actual: " . $now . " Siguiente hora: " . $nextHour);
			$expiringTokens = Authorization::whereBetween('expires_at', [$now, $nextHour])
				->whereNotNull('refresh_token')
				->get();
			if($expiringTokens->isEmpty()){
				$this->info('Sin tokens para actualizar');
				return;
			}
			$tokens = Authorization::all();
			foreach($tokens as $token){
				$accesToken = $token->acces_token;
				$this->info("Acces token: \n" . $accesToken);
				if (Carbon::parse($token->expires_at)->isBefore($nextHour)) {
					$newAccessToken = getNewAccessToken($token->refresh_token);
				}
			}
		}
}
