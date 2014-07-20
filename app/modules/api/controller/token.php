<?php
namespace app\modules\api\controller;

use app\modules\api\model as modelo;

class Token extends \DMS\Tornado\Controller
{
	
	public function getToken()
	{
		
		$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
		$pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
		
		$credenciales = \DMS\Tornado\Tornado::getInstance()->config('api');
		
		if (
			password_verify($user, $credenciales['tokenUser']) !== false &&
			password_verify($pass, $credenciales['tokenPass']) !== false
		) {
			$estado = true;
		} else {
			$estado = false;
		}
		
		// se genera un token aleatorio
		$cripto = new  \DMS\Libs\Cripto();
		$tokenHash = $cripto->crearHash(55);
		
		// se registra el token en la base de datos
		$this->loadModel('api|token');
		
		$token = new modelo\Token();
		$token->setToken($tokenHash);
		$token->setVida(time() + $credenciales['tokenVida']);
		$token->setEstado($estado);
		$token->registrar();
		
		// se devuelve el token generado
		echo $tokenHash;
		
	}
	
}
