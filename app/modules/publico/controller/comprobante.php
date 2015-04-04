<?php
namespace app\modules\publico\controller;

use DMS\Tornado\Controller;
use app\modules\publico\model as Modelo;

class Comprobante extends Controller
{
	
	public function panel()
	{
		$this->_validaSesionExistente();
		
		$this->loadModel('publico|comprobante');
		
		$comprobante = new Modelo\Comprobante();
		
		if ($_SESSION['usrRoot'])
			$comprobante->setEmail('ALL');
		else
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
		
		// se validan permisos de descarga
		if (!$_SESSION['usrRoot']) {
			
			$this->loadModel('publico|comprobante');

			$modComprobante = new Modelo\Comprobante();
			$modComprobante->setEmail($_SESSION['usrEmail']);
			$modComprobante->setArchivo($pPDF);
			
			if ($modComprobante->permisoDescarga() == false) {
				header('Location: ' . URLFRIENDLY . 'panel');
				exit();
			}
			
		}
		
		$cripto = $this->app->container('cripto');
		
		$passCript = $this->app->config('passCript');
		$pdf = $cripto->desencriptar($pPDF, $passCript) . '.pdf';

		$path = __DIR__ . '/../../../../protected/' . $pdf;
		
		if (file_exists($path) == false) {
			$this->loadView('publico|noencontrado');
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
