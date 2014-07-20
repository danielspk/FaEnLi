<?php
namespace app\modules\api\controller;

use app\modules\api\model as modelo;
use app\modules\api\controller as controlador;

class Usuario extends \DMS\Tornado\Controller
{
	
	private $_contToken;
	
	public function __construct()
	{
		$this->loadController('api|token');
		
		$this->_contToken = new controlador\Token();
	}

	public function getUsuarios()
	{
		if ($this->_contToken->validaCredenciales() === false) {
			return;
		}
		
		$usrNombre = filter_input(INPUT_POST, 'usrNombre', FILTER_SANITIZE_STRING);
		$usrApellido = filter_input(INPUT_POST, 'usrApellido', FILTER_SANITIZE_STRING);
		$usrEmail = filter_input(INPUT_POST, 'usrEmail', FILTER_SANITIZE_STRING);
		
		if ($usrNombre == null)
			$usrNombre = '%';
		
		if ($usrApellido == null)
			$usrApellido = '%';
		
		if ($usrEmail == null)
			$usrEmail = '%';
		
		$this->loadModel('api|usuario');
		
		$usuario = new modelo\Usuario();
		$usuario->setNombre($usrNombre);
		$usuario->setApellido($usrApellido);
		$usuario->setEmail($usrEmail);
		
		$usuarios = $usuario->consultar();
		
		echo json_encode(array('estado' => 'ok', 'usuarios' => $usuarios));
		
	}
	
}
