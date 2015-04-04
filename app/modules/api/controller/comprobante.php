<?php
namespace app\modules\api\controller;

use DMS\Tornado\Tornado;
use DMS\Tornado\Controller;
use app\modules\api\model as Modelo;

/**
 * Class Comprobante
 * @package app\modules\api\controller
 */
class Comprobante extends Controller
{
    /**
     * @var Token Controlador Token
     */
	private $_contToken;

    /**
     * @var string Path a PDFs
     */
	private $_path;

    /**
     * Contructor del controlador
     * @param Tornado $pApp Instancia de Tornado
     */
	public function __construct(Tornado $pApp)
	{
        parent::__construct($pApp);

		$this->loadController('api|token');
		
		$this->_contToken = new Token($pApp);
		$this->_path =__DIR__ . '/../../../../protected/';
	}

	public function registrar()
	{
		if ($this->_contToken->validaCredenciales() === false) {
			return;
		}
		
		$comprobantes = json_decode($_POST['comprobantes']);
	
		if ($this->_validaJsonComprobantes($comprobantes) === false) {
			return;
		}

		// se registran los comprobantes
		$this->loadModel('api|comprobante');
	
		$modComprobante = new Modelo\Comprobante();
		$modComprobante->setComprobantes($comprobantes);
		$modComprobante->registrar();
		
		echo json_encode(array('estado' => 'ok', 'detalle' => 'Comprobantes registrados exitosamente'));
	}

	public function borrar()
	{
		if ($this->_contToken->validaCredenciales() === false) {
			return;
		}
		
		$comprobantes = json_decode($_POST['comprobantes']);
	
		if ($this->_validaJsonComprobantes($comprobantes, false) === false) {
			return;
		}

        $cripto = $this->app->container('cripto');
		$passCript = $this->app->config('passCript');
		
		// se borran los comprobantes
		$this->loadModel('api|comprobante');
	
		$modComprobante = new Modelo\Comprobante();
		
		// se borran los archivos
		foreach ($comprobantes->comprobantes as $comprobante) {
			
			$modComprobante->setTipo($comprobante->tipo);
			$modComprobante->setPtoVenta($comprobante->ptoventa);
			$modComprobante->setNumero($comprobante->numero);
			$row = $modComprobante->getArchivo();
			
			if ($row) {
				
				$path = $this->_path . $cripto->desencriptar($row->archivo, $passCript) . '.pdf';
				if (file_exists($path)) {
					unlink($path);
				}
			}
			
		} 
		
		// se borran los comprobantes
		$modComprobante->setComprobantes($comprobantes);
		$modComprobante->borrar();
		
		echo json_encode(array('estado' => 'ok', 'detalle' => 'Comprobantes eliminados exitosamente'));
		
	}
	
	private function _validaJsonComprobantes($pComprobantes, $pFull = true)
	{
		
		$comprobantes = $pComprobantes;
		
		// se valida el formato del json
		if (! is_object($comprobantes) || ! isset($comprobantes->comprobantes)) {
			$this->_formatoJsonNoValido();
			return false;
		}

        $cripto = $this->app->container('cripto');
		$passCript = $this->app->config('passCript');
		
		$ciclo = 0;
        $valida = $this->app->container('valida');
		
		foreach($comprobantes->comprobantes as $comprobante) {
			
			$ciclo++;
			
			if (!isset($comprobante->tipo)) {
				$this->_formatoJsonNoValido('Campo \'tipo\' no definido', $ciclo);
				return false;
			} else if(strlen(trim($comprobante->tipo)) < 1) {
				$this->_formatoJsonNoValido('Campo \'tipo\' con errores', $ciclo);
				return false;
			}
		
			if (!isset($comprobante->ptoventa)) {
				$this->_formatoJsonNoValido('Campo \'ptoventa\' no definido', $ciclo);
				return false;
			} else if(! $valida->entero($comprobante->ptoventa) || $comprobante->ptoventa == 0) {
				$this->_formatoJsonNoValido('Campo \'ptoventa\' con errores', $ciclo);
				return false;
			}
		
			if (!isset($comprobante->numero)) {
				$this->_formatoJsonNoValido('Campo \'numero\' no definido', $ciclo);
				return false;
			} else if(! $valida->entero($comprobante->numero) || $comprobante->numero == 0 || $comprobante->numero > 99999999) {
				$this->_formatoJsonNoValido('Campo \'numero\' con errores', $ciclo);
				return false;
			}
			
			// se omiten las otras validaciones si el chequeo no es full
			if ($pFull !== true) {
				continue;
			}
			
			if (!isset($comprobante->fecha)) {
				$this->_formatoJsonNoValido('Campo \'fecha\' no definido', $ciclo);
				return false;
			} else if(! $valida->fecha($comprobante->fecha)) {
				$this->_formatoJsonNoValido('Campo \'fecha\' con errores', $ciclo);
				return false;
			}
			
			if (!isset($comprobante->moneda)) {
				$this->_formatoJsonNoValido('Campo \'moneda\' no definido', $ciclo);
				return false;
			} else if(strlen(trim($comprobante->moneda)) < 1) {
				$this->_formatoJsonNoValido('Campo \'moneda\' con errores', $ciclo);
				return false;
			}
			
			if (!isset($comprobante->importe)) {
				$this->_formatoJsonNoValido('Campo \'importe\' no definido', $ciclo);
				return false;
			} else if(! $valida->decimal($comprobante->importe) || $comprobante->importe <= 0) {
				$this->_formatoJsonNoValido('Campo \'importe\' con errores', $ciclo);
				return false;
			}
			
			if (!isset($comprobante->archivo)) {
				$this->_formatoJsonNoValido('Campo \'archivo\' no definido', $ciclo);
				return false;
			} else if(strlen(trim($comprobante->archivo)) < 1) {
				$this->_formatoJsonNoValido('Campo \'archivo\' con errores', $ciclo);
				return false;
			} else {
				
				$path = $this->_path . $comprobante->archivo . '.pdf';
				
				if(!file_exists($path)) {
					
					$this->_formatoJsonNoValido('El archivo no se encuentra en el servidor', $ciclo);
					return false;
				
				} else {
					
					$comprobante->archivo = $cripto->encriptar($comprobante->archivo, $passCript);
					
				}

			}
			
			if (!isset($comprobante->usuarios)) {
				$this->_formatoJsonNoValido('Sección \'usuarios\' no definida', $ciclo);
				return false;
			} else {
				
				foreach($comprobante->usuarios as $usuario) {
					if (! $valida->email($usuario)) {
						$this->_formatoJsonNoValido('Campo \'email\' con dirección no válida', $ciclo);
						return false;
					}
				}
			}
			
		}
		
		return true;
		
	}
	
	private function _formatoJsonNoValido($pTexto = 'Formato JSON no válido', $pCiclo = 0)
	{
		echo json_encode(array('estado' => 'error', 'comprobante' => $pCiclo, 'detalle' => $pTexto));
		return;
	}
	
}
