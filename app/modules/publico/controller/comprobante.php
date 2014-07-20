<?php
namespace app\modules\publico\controller;

use app\modules\publico\model as modelo;

class Comprobante extends \DMS\Tornado\Controller
{
	
	public function panel()
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
	
    public function descarga($pPDF = null)
    {
		
		$this->_validaSesionExistente();
		
		$cripto = new \DMS\Libs\Cripto();
		
		$pdf = $cripto->desencriptar($pPDF, 'clave') . '.pdf';

		$path = __DIR__ . '/../../../../protected/' . $pdf;
		
		if (file_exists($path) == false) {
			return;
		}
		
		$length = filesize($path);
		header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename='. $pdf);
		header('Content-Length: ' . $length);
		readfile($path);
		
    }
	
	private function _validaSesionExistente()
	{
		session_start();

		if (!isset($_SESSION['iniciada']) || $_SESSION['iniciada'] !== true) {
			header('Location: ' . URLFRIENDLY . 'login');
			exit();
		}
	}
	
}
