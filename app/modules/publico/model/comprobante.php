<?php
namespace app\modules\publico\model;

class Comprobante
{
	/* Atributos */
	private $_conex;
	private $_email;
	private $_archivo;
	
	/* Getters y Setters */
	public function setEmail($pDato) { $this->_email = $pDato; }
	public function setArchivo($pDato) { $this->_archivo = $pDato; }
	
	/* Constructor */
	public function __construct()
	{
		$this->_conex = \DMS\Libs\DataBase::conectar(\DMS\Tornado\Tornado::getInstance()->config('db'));
	}
	
	/* Métodos públicos */
	public function recuperarCantidadComprobantes()
	{
		$sql = '
			SELECT 
				COUNT(*) AS cantid
			FROM 
				comprobantes c
			INNER JOIN 
				comprobantes_usuarios cu
			ON 
				c.tipo = cu.tipo AND
				c.ptoventa = cu.ptoventa AND
				c.numero = cu.numero
			WHERE
				cu.email = :email
			';
		
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':email', $this->_email, \PDO::PARAM_STR);
		$db->execute();
		
		$row = $db->fetchObject();
		
		$this->_conex->desconectar();
		
		return $row->cantid;
	}
	
	public function recuperarComprobantes()
	{
		
		$sqlSelect = '
			SELECT 
				DATE_FORMAT(c.fecha, \'%d/%m/%Y\') as fecha, c.tipo, 
				c.ptoventa, c.numero, c.moneda, c.importe, c.archivo
		';
		
		if ($this->_email !== 'ALL') {
			
			$sql = $sqlSelect . '
			FROM 
				comprobantes c
			INNER JOIN 
				comprobantes_usuarios cu
			ON 
				c.tipo = cu.tipo AND
				c.ptoventa = cu.ptoventa AND
				c.numero = cu.numero 
			WHERE 
				cu.email = :email
			ORDER BY
				c.fecha desc, c.tipo, c.ptoventa, c.numero
			';
		
			$db = $this->_conex->prepare($sql);			
			$db->bindParam(':email', $this->_email, \PDO::PARAM_STR);

		} else {
			
			$sql = $sqlSelect . '
			FROM 
				comprobantes c
			ORDER BY
				c.fecha desc, c.tipo, c.ptoventa, c.numero
			';
		
			$db = $this->_conex->prepare($sql);			
			
		}
		
		
		$db->execute();
		
		$comprobantes = $db->fetchAll(\PDO::FETCH_OBJ);
		
		$this->_conex->desconectar();
		
		return $comprobantes;
	}
	
	public function permisoDescarga()
	{
		$sql = '
			SELECT 
				COUNT(*) AS permiso
			FROM 
				comprobantes c
			INNER JOIN 
				comprobantes_usuarios cu
			ON 
				c.tipo = cu.tipo AND
				c.ptoventa = cu.ptoventa AND
				c.numero = cu.numero
			WHERE
				c.archivo = :archivo AND
				cu.email = :email
		';
		
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':archivo', $this->_archivo, \PDO::PARAM_STR);
		$db->bindParam(':email', $this->_email, \PDO::PARAM_STR);
		$db->execute();
		
		$row = $db->fetchObject();
		
		$this->_conex->desconectar();
		
		return ($row->permiso == 1) ? true : false;
	}
	
}
