<?php
namespace App\Modules\Publico\Controller;

use DMS\Tornado\Controller;
use App\Modules\Publico\Model\Comprobante as ComprobanteMod;

class Comprobante extends Controller
{
	
	public function panel()
	{
		$this->validaSesionExistente();

		$comprobante = new ComprobanteMod();
		
		if ($_SESSION['usrRoot'])
			$comprobante->setEmail('ALL');
		else
			$comprobante->setEmail($_SESSION['usrEmail']);
			
		$comprobantes = $comprobante->recuperarComprobantes();
		
		$vars = array(
            'app' => $this->app,
			'nombre' => $_SESSION['usrNombre'],
			'apellido' => $_SESSION['usrApellido'],
			'email' => $_SESSION['usrEmail'],
			'comprobantes' => $comprobantes
		);

        $this->app->render('app/modules/Publico/View/panel.tpl.php', $vars);
	}
	
    public function descarga($pPDF = null)
    {
		
		$this->validaSesionExistente();
		
		// se validan permisos de descarga
		if (!$_SESSION['usrRoot']) {

			$modComprobante = new ComprobanteMod();
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
            $this->app->render('app/modules/Publico/View/noencontrado.tpl.php', ['app' => $this->app]);
			return;
		}
		
		$length = filesize($path);
		header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename='. $pdf);
		header('Content-Length: ' . $length);
		readfile($path);
    }
	
	private function validaSesionExistente()
	{
		session_start();

		if (!isset($_SESSION['iniciada']) || $_SESSION['iniciada'] !== true) {
			header('Location: ' . URLFRIENDLY . 'login');
			exit();
		}
	}
	
}
