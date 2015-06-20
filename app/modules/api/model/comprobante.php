<?php
namespace App\Modules\Api\Model;

use DMS\Tornado\Tornado;

class Comprobante
{
	/* Atributos */
	private $_conex;
	private $_comprobantes;
	private $_tipo;
	private $_ptoventa;
	private $_numero;
	
	/* Getters y Setters */
	public function setComprobantes($pDato) { $this->_comprobantes = $pDato; }
	public function setTipo($pDato) { $this->_tipo = $pDato; }
	public function setPtoVenta($pDato) { $this->_ptoventa = $pDato; }
	public function setNumero($pDato) { $this->_numero = $pDato; }
	
	/* Constructor */
	public function __construct()
	{
        $this->_conex = Tornado::getInstance()->container('conex');
	}
	
	/* Métodos públicos */
	public function registrar()
	{

		$this->_conex->beginTransaction();
		
		foreach($this->_comprobantes->comprobantes as $comprobante) {
			
			$this->_eliminar($comprobante);
			
			$sql = 'INSERT INTO comprobantes (tipo, ptoventa, numero, fecha, moneda, importe, archivo)
					VALUES (:tipo, :ptoventa, :numero, :fecha, :moneda, :importe, :archivo)';

            /** @var \PDOStatement $db */
			$db = $this->_conex->prepare($sql);
			$db->bindParam(':tipo', $comprobante->tipo, \PDO::PARAM_STR);
			$db->bindParam(':ptoventa', $comprobante->ptoventa, \PDO::PARAM_INT);
			$db->bindParam(':numero', $comprobante->numero, \PDO::PARAM_INT);
			$db->bindParam(':fecha', $comprobante->fecha, \PDO::PARAM_STR);
			$db->bindParam(':moneda', $comprobante->moneda, \PDO::PARAM_STR);
			$db->bindParam(':importe', $comprobante->importe, \PDO::PARAM_STR);
			$db->bindParam(':archivo', $comprobante->archivo, \PDO::PARAM_STR);
			$db->execute();
			
			foreach($comprobante->usuarios as $usuario) {
				
				unset($sql);
				unset($db);
				
				$sql = 'INSERT INTO comprobantes_usuarios (tipo, ptoventa, numero, email)
					VALUES (:tipo, :ptoventa, :numero, :email)';

                /** @var \PDOStatement $db */
				$db = $this->_conex->prepare($sql);
				$db->bindParam(':tipo', $comprobante->tipo, \PDO::PARAM_STR);
				$db->bindParam(':ptoventa', $comprobante->ptoventa, \PDO::PARAM_INT);
				$db->bindParam(':numero', $comprobante->numero, \PDO::PARAM_INT);
				$db->bindParam(':email', $usuario, \PDO::PARAM_STR);
				$db->execute();
				
			}
			
			unset($sql);
			unset($db);
				
		}
		
		$this->_conex->commit();
		
		$this->_conex->desconectar();
		
	}
	
	public function borrar()
	{
		$this->_conex->beginTransaction();
		
		foreach($this->_comprobantes->comprobantes as $comprobante) {
			$this->_eliminar($comprobante);
		}
		
		$this->_conex->commit();
		
		$this->_conex->desconectar();
		
	}
	
	public function getArchivo()
	{
		$sql = 'SELECT archivo FROM comprobantes WHERE tipo = :tipo AND ptoventa = :ptoventa AND numero = :numero';

        /** @var \PDOStatement $db */
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':tipo', $this->_tipo, \PDO::PARAM_STR);
		$db->bindParam(':ptoventa', $this->_ptoventa, \PDO::PARAM_INT);
		$db->bindParam(':numero', $this->_numero, \PDO::PARAM_INT);
		$db->execute();

		$row = $db->fetchObject();

		$this->_conex->desconectar();
		
		return $row;
	}
	
	private function _eliminar($pComprobante)
	{
		
		$comprobante = $pComprobante;
		
		$sql = 'DELETE FROM comprobantes WHERE tipo = :tipo AND ptoventa = :ptoventa AND numero = :numero';

        /** @var \PDOStatement $db */
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':tipo', $comprobante->tipo, \PDO::PARAM_STR);
		$db->bindParam(':ptoventa', $comprobante->ptoventa, \PDO::PARAM_INT);
		$db->bindParam(':numero', $comprobante->numero, \PDO::PARAM_INT);
		$db->execute();

		unset($sql);
		unset($db);

		$sql = 'DELETE FROM comprobantes_usuarios WHERE tipo = :tipo AND ptoventa = :ptoventa AND numero = :numero';

        /** @var \PDOStatement $db */
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':tipo', $comprobante->tipo, \PDO::PARAM_STR);
		$db->bindParam(':ptoventa', $comprobante->ptoventa, \PDO::PARAM_INT);
		$db->bindParam(':numero', $comprobante->numero, \PDO::PARAM_INT);
		$db->execute();

		unset($sql);
		unset($db);

	}
	
}
