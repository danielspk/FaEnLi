<?php
namespace App\Modules\Instalacion\Model;

use DMS\Tornado\Tornado;

class Wizard
{
	/* Atributos */
	private $_conex;
	private $_adminUser;
	private $_adminPass;
	
	/* Getters y Setters */
	public function setAdminUser($pDato) { $this->_adminUser = $pDato; }
	public function setAdminPass($pDato) { $this->_adminPass = $pDato; }
	
	/* Constructor */
	public function __construct($pConex = null)
	{
        $app = Tornado::getInstance();

		if ($pConex)
            $app->register('conex.config', $pConex);

        $this->_conex = $app->container('conex');
	}
	
	/* Métodos públicos */
	public function crearTablas()
	{
		// se eliminan tablas existentes .....

		//tabla de comprobantes
		$sql = '
		CREATE TABLE IF NOT EXISTS `comprobantes` (
			`tipo` varchar(30) NOT NULL,
			`ptoventa` smallint(5) unsigned NOT NULL,
			`numero` int(10) unsigned NOT NULL,
			`fecha` date NOT NULL,
			`moneda` varchar(25) NOT NULL,
			`importe` decimal(10,4) NOT NULL,
			`archivo` varchar(255) NOT NULL,
			PRIMARY KEY (`tipo`,`ptoventa`,`numero`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		';
		$this->_conex->exec($sql);
		unset($sql);
		
		//tabla de comprobantes por usuarios
		$sql = '
		CREATE TABLE IF NOT EXISTS `comprobantes_usuarios` (
			`tipo` varchar(30) NOT NULL,
			`ptoventa` smallint(5) unsigned NOT NULL,
			`numero` int(10) unsigned NOT NULL,
			`email` varchar(75) NOT NULL,
			PRIMARY KEY (`tipo`,`ptoventa`,`numero`,`email`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		';
		$this->_conex->exec($sql);
		unset($sql);
		
		//tabla de tokens
		$sql = '
		CREATE TABLE IF NOT EXISTS `tokens` (
			`token` varchar(55) NOT NULL,
			`vida` int(10) unsigned NOT NULL,
			`estado` tinyint(1) NOT NULL,
			PRIMARY KEY (`token`,`vida`,`estado`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
		$this->_conex->exec($sql);
		unset($sql);
		
		//tabla de usuarios
		$sql = '
		CREATE TABLE IF NOT EXISTS `usuarios` (
			`email` varchar(75) NOT NULL,
			`nombre` varchar(55) NOT NULL,
			`apellido` varchar(65) NOT NULL,
			`clave` varchar(60) NOT NULL,
			`reset_codigo` varchar(98) DEFAULT NULL,
			`reset_vida` int(10) unsigned DEFAULT NULL,
			`root` tinyint(1) NOT NULL,
			PRIMARY KEY (`email`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		';
		$this->_conex->exec($sql);
		unset($sql);

		$this->_conex->desconectar();
	}

	public function registrarSuperusuario()
	{
		
		$sql = "INSERT INTO usuarios (email, nombre, apellido, clave, root) VALUES (:email, 'Super', 'Usuario', :clave, 1)";

        /** @var \PDOStatement $db */
		$db = $this->_conex->prepare($sql);
		$db->bindParam(':email', $this->_adminUser, \PDO::PARAM_STR);
		$db->bindParam(':clave', $this->_adminPass, \PDO::PARAM_STR);
		$db->execute();
		
		$this->_conex->desconectar();
		
	}
	
}
