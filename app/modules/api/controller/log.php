<?php
namespace app\modules\api\controller;

use DMS\Tornado\Tornado;
use DMS\Tornado\Controller;

class Log extends Controller
{
	
	private $_contToken;
	private $_path;
	
	public function __construct(Tornado $pApp)
	{
        parent::__construct($pApp);

		$this->loadController('api|token');
		
		$this->_contToken = new Token($pApp);
		$this->_path = __DIR__ . '/../../../log/log.log';
	}
	
	public function borrarLogs()
	{
		if ($this->_contToken->validaCredenciales() === false) {
			return;
		}
		
		if (file_exists($this->_path)) {
			
			unlink($this->_path);
		}
		
		echo json_encode(array('estado' => 'ok', 'detalle' => 'El contenido del archivo de logs fue correctamente eliminado'));
	}
	
	public function getLogs()
	{
		if ($this->_contToken->validaCredenciales() === false) {
			return;
		}
		
		// se recupera el contenido del archivo de logs
		if (!file_exists($this->_path)) {
			echo json_encode(array('estado' => 'vacio', 'descripcion' => 'No hay contenido de logs'));
			return;
		}
		
		$contLog = file_get_contents($this->_path);
		
		$contLogReg = explode("\n\n", $contLog);
		
		// se elimina ultimo posicion (siempre es vacio)	
		unset($contLogReg[count($contLogReg)-1]);
		
		echo json_encode(array('estado' => 'ok', 'contenido' => $contLogReg));
		
	}
	
}
