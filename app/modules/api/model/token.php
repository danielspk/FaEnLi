<?php
namespace app\modules\api\model;

class Token
{
	/* Atributos */
	private $_conex;
	private $_token;
	private $_vida;
	private $_estado;
	
	/* Getters y Setters */
	public function setToken($pDato) { $this->_token = $pDato; }
	public function setVida($pDato) { $this->_vida = $pDato; }
	public function setEstado($pDato) { $this->_estado = $pDato; }
	
	/* Constructor */
	public function __construct()
	{
		$this->_conex = \DMS\PHPLibs\DataBase::conectar(\DMS\Tornado\Tornado::getInstance()->config('db'));
	}
	
	/* Métodos públicos */
	public function registrar()
	{
		$this->_eliminarVencidos();
		
		$sql = 'INSERT INTO tokens (token, vida, estado) VALUES (:token, :vida, :estado)';
		
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':token', $this->_token, \PDO::PARAM_STR);
		$db->bindParam(':vida', $this->_vida, \PDO::PARAM_INT);
		$db->bindParam(':estado', $this->_estado, \PDO::PARAM_BOOL);
		$db->execute();
	
		$this->_conex->desconectar();
	}
	
	public function verVigencia()
	{
		$sql = 'SELECT COUNT(*) AS cantid FROM tokens WHERE estado = true AND token = :token AND vida >= ' . time();
		
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':token', $this->_token, \PDO::PARAM_STR);
		$db->execute();
	
		$row = $db->fetchObject();
		
		$this->_conex->desconectar();
		
		return ($row->cantid == 1) ? true : false;
	}
	
	/* Métodos privados */
	public function _eliminarVencidos()
	{
		$sql = 'DELETE FROM tokens WHERE vida < ' . time();
		
		$this->_conex->exec($sql);

		$this->_conex->desconectar();
	}
	
}
