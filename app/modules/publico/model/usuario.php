<?php
namespace app\modules\publico\model;

use DMS\Tornado\Tornado;

class Usuario
{
	/* Atributos */
	private $_conex;
	private $_email;
	private $_nombre;
	private $_apellido;
	private $_clave;
	private $_reset_codigo;
	private $_reset_vida;
	
	/* Getters y Setters */
	public function setEmail($pDato) { $this->_email = $pDato; }
	public function setNombre($pDato) { $this->_nombre = $pDato; }
	public function setApellido($pDato) { $this->_apellido = $pDato; }
	public function setClave($pDato) { $this->_clave = password_hash($pDato, PASSWORD_BCRYPT, array('cost' => 11)); }
	public function setResetCodigo($pDato) { $this->_reset_codigo = $pDato; }
	public function setResetVida($pDato) { $this->_reset_vida = $pDato; }
	
	/* Constructor */
	public function __construct()
	{
        $this->_conex = Tornado::getInstance()->container('conex');
	}
	
	/* Métodos públicos */
	public function validarCredenciales()
	{
		$sql = 'SELECT COUNT(email) AS cantid FROM usuarios WHERE email = :email AND clave = :clave';

        /** @var \PDOStatement $db */
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':email', $this->_email, \PDO::PARAM_STR);
		$db->bindParam(':clave', $this->_clave, \PDO::PARAM_STR);
		$db->execute();
		
		$row = $db->fetchObject();
		
		$this->_conex->desconectar();
		
		return ($row->cantid == 1) ? true : false;
	}
	
	public function recuperarDatos()
	{
		$sql = 'SELECT * FROM usuarios WHERE email = :email';

        /** @var \PDOStatement $db */
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':email', $this->_email, \PDO::PARAM_STR);
		$db->execute();
		
		$usuario = $db->fetchObject();
		
		$this->_conex->desconectar();
		
		return $usuario;
	}
	
	public function grabarLinkRecupero()
	{
		$sql = 'UPDATE usuarios SET reset_codigo = :reset_codigo, reset_vida = :reset_vida WHERE email = :email';

        /** @var \PDOStatement $db */
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':reset_codigo', $this->_reset_codigo, \PDO::PARAM_STR);
		$db->bindParam(':reset_vida', $this->_reset_vida, \PDO::PARAM_INT);
		$db->bindParam(':email', $this->_email, \PDO::PARAM_STR);
		$db->execute();
		
		$this->_conex->desconectar();
	}
	
	public function validarCodigoRestablecimiento()
	{
		$sql = 'SELECT COUNT(*) AS cantid FROM usuarios WHERE email = :email AND reset_codigo = :reset_codigo AND reset_vida >= :reset_vida';

        /** @var \PDOStatement $db */
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':email', $this->_email, \PDO::PARAM_STR);
		$db->bindParam(':reset_codigo', $this->_reset_codigo, \PDO::PARAM_STR);
		$db->bindParam(':reset_vida', $this->_reset_vida, \PDO::PARAM_INT);
		$db->execute();	
		
		$row = $db->fetchObject();
		
		$this->_conex->desconectar();

		return ($row->cantid == 1) ? true : false;
	}
	
	public function actualizarClave()
	{
		$sql = 'UPDATE usuarios SET clave = :clave, reset_codigo = NULL, reset_vida = NULL WHERE email = :email';

        /** @var \PDOStatement $db */
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':clave', $this->_clave, \PDO::PARAM_STR);
		$db->bindParam(':email', $this->_email, \PDO::PARAM_STR);
		$db->execute();
		
		$this->_conex->desconectar();
	}
	
	public function grabarRegistro()
	{
		$sql = 'INSERT INTO usuarios(email, nombre, apellido, clave) VALUES (:email, :nombre, :apellido, :clave)';

        /** @var \PDOStatement $db */
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':email', $this->_email, \PDO::PARAM_STR);
		$db->bindParam(':nombre', $this->_nombre, \PDO::PARAM_STR);
		$db->bindParam(':apellido', $this->_apellido, \PDO::PARAM_STR);
		$db->bindParam(':clave', $this->_clave, \PDO::PARAM_STR);
		$db->execute();
		
		$this->_conex->desconectar();
	}
	
}