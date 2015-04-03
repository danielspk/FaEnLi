<?php
namespace app\modules\api\controller;

use DMS\Tornado\Tornado;
use DMS\Tornado\Controller;
use app\modules\api\model as Modelo;

class Usuario extends Controller
{
	
	private $_contToken;
	
	public function __construct(Tornado $pApp)
	{
        parent::__construct($pApp);

		$this->loadController('api|token');
		
		$this->_contToken = new Token($pApp);
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
		
		$usuario = new Modelo\Usuario();
		$usuario->setNombre($usrNombre);
		$usuario->setApellido($usrApellido);
		$usuario->setEmail($usrEmail);
		
		$usuarios = $usuario->consultar();
		
		echo json_encode(array('estado' => 'ok', 'usuarios' => $usuarios));
		
	}
	
}
