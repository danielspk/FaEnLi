<?php
namespace App\Modules\Api\Controller;

use DMS\Tornado\Controller;
use App\Modules\Api\Model\Token as TokenMod;

/**
 * Class Token
 * @package App\Modules\Api\Controller
 */
class Token extends Controller
{
	
	public function getToken()
	{
		
		$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
		$pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
		
		$credenciales = $this->app->config('api');
		
		if (
			password_verify($user, $credenciales['tokenUser']) !== false &&
			password_verify($pass, $credenciales['tokenPass']) !== false
		) {
			$estado = true;
		} else {
			$estado = false;
		}
		
		// se genera un token aleatorio
		$cripto = $this->app->container('cripto');
		$tokenHash = $cripto->crearHash(55);
		
		// se registra el token en la base de datos
		$token = new TokenMod();
		$token->setToken($tokenHash);
		$token->setVida(time() + $credenciales['tokenVida']);
		$token->setEstado($estado);
		$token->registrar();
		
		// se devuelve el token generado
		echo json_encode(array('token' => $tokenHash));
		
	}
	
	public function validaCredenciales()
	{
		
		$tokenHash = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
		$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
		$pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
		
		// se determina si el token esta vigente
		$token = new TokenMod();
		$token->setToken($tokenHash);
		
		if ($token->verVigencia() == false) {
			echo json_encode(array('estado' => 'error', 'descripcion' => 'token no válido'));
			return false;
		}
		
		$credenciales = $this->app->config('api');

		if (
			password_verify($user, $credenciales['apiUser']) === false ||
			password_verify($pass, $credenciales['apiPass']) === false
		) {
			echo json_encode(array('estado' => 'error', 'descripcion' => 'credenciales no válidas'));
			return false;
		}
		
		return true;
		
	}
	
}
