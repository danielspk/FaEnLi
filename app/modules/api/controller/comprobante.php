<?php
namespace app\modules\api\controller;

use app\modules\api\model as modelo;
use app\modules\api\controller as controlador;

class Comprobante extends \DMS\Tornado\Controller
{
	
	private $_contToken;
	
	public function __construct()
	{
		$this->loadController('api|token');
		
		$this->_contToken = new controlador\Token();
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
		
		
		
		
		exit();
		
		
		
		// se recorre los comprobantes
		$this->loadModel('api|comprobante');
		
		$modComprobante = new modelo\Comprobante();
		
		
		
		
		
		
		
		$usuario->setNombre($usrNombre);
		$usuario->setApellido($usrApellido);
		$usuario->setEmail($usrEmail);
		
		$usuarios = $usuario->consultar();
		
		echo json_encode(array('estado' => 'ok', 'usuarios' => $usuarios));
		
	}
	
	private function _validaJsonComprobantes($pComprobantes)
	{
		
		$comprobantes = $pComprobantes;
		
		// se valida el formato del json
		if (! is_object($comprobantes) || ! isset($comprobantes->comprobantes)) {
			$this->_formatoJsonNoValido();
			return false;
		}
		
		$ciclo = 0;
		$valida = new \DMS\Libs\Valida();
		
		foreach($comprobantes->comprobantes as $comprobante) {
			
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
			} else if(! $valida->entero($comprobante->ptoventa) || $comprobante->ptoventa = 0) {
				$this->_formatoJsonNoValido('Campo \'ptoventa\' con errores', $ciclo);
				return false;
			}
		
			if (!isset($comprobante->numero)) {
				$this->_formatoJsonNoValido('Campo \'numero\' no definido', $ciclo);
				return false;
			} else if(! $valida->entero($comprobante->numero) || $comprobante->numero = 0 || $comprobante->numero > 99999999) {
				$this->_formatoJsonNoValido('Campo \'numero\' con errores', $ciclo);
				return false;
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
			
			$ciclo++;
		}
		
		return true;
		
	}
	
	private function _formatoJsonNoValido($pTexto = 'Formato JSON no válido', $pCiclo = 0)
	{
		echo json_encode(array('estado' => 'error', 'comprobante' => $pCiclo, 'detalle' => $pTexto));
		return;
	}
	
}
