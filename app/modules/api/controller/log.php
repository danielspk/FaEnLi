<?php
namespace app\modules\api\controller;

use app\modules\api\model as modelo;

class Api extends \DMS\Tornado\Controller
{
	
	public function getLogs()
	{
		$this->_validaSesionExistente();
		
		$this->loadModel('publico|comprobante');
		
		$comprobante = new modelo\Comprobante();
		$comprobante->setEmail($_SESSION['usrEmail']);
		
		$comprobantes = $comprobante->recuperarComprobantes();
		
		$vars = array(
			'nombre' => $_SESSION['usrNombre'],
			'apellido' => $_SESSION['usrApellido'],
			'email' => $_SESSION['usrEmail'],
			'comprobantes' => $comprobantes
		);
		
		$this->loadView('publico|panel', $vars);
	}
	
}
