<?php
namespace app\modules\api\model;

class Comprobante
{
	/* Atributos */
	private $_conex;
	private $_nombre;
	private $_apellido;
	private $_email;
	
	/* Getters y Setters */
	public function setNombre($pDato) { $this->_nombre = $pDato; }
	public function setApellido($pDato) { $this->_apellido = $pDato; }
	public function setEmail($pDato) { $this->_email = $pDato; }
	
	/* Constructor */
	public function __construct()
	{
		$this->_conex = \DMS\Libs\DataBase::conectar(\DMS\Tornado\Tornado::getInstance()->config('db'));
	}
	
	/* Métodos públicos */
	public function registrar()
	{
		
	}
	
	
	
	
	
	public function consultar()
	{
	
		$sql = 'SELECT email, nombre, apellido FROM usuarios WHERE email like :email AND nombre like :nombre AND apellido like :apellido';
		
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':email', $this->_email, \PDO::PARAM_STR);
		$db->bindParam(':nombre', $this->_nombre, \PDO::PARAM_STR);
		$db->bindParam(':apellido', $this->_apellido, \PDO::PARAM_STR);
		$db->execute();
	
		$usuarios = $db->fetchAll(\PDO::FETCH_OBJ);
		
		$this->_conex->desconectar();
		
		return $usuarios;
		
	}

}
