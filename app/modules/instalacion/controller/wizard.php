<?php
namespace app\modules\instalacion\controller;

use app\modules\instalacion\model as modelo;

class Wizard extends \DMS\Tornado\Controller
{

	public function instalar()
	{
		if (isset($_POST['btnInstalar'])) {
			$this->_procesarInstalacion();
			return;
		}
		
		$this->loadView('instalacion|wizard');
	}

	private function _procesarInstalacion()
	{
		
		$pathT = __DIR__ . '/../../../config/config.template.php';
		$pathO = __DIR__ . '/../../../config/config.php';
		$pathA = __DIR__ . '/../../../../.htaccess';
		
		// se crea la estructura de la base de datos
		$this->loadModel('instalacion|wizard');
	
		$modWizard = new modelo\Wizard(array(
			'motor'		=> 'MYSQL',
			'host'      => $_POST['db_host'],
			'base'      => $_POST['db_base'],
			'user'      => $_POST['db_user'],
			'pass'      => $_POST['db_pass'],
			'collation' => 'utf8'
		));
		$modWizard->crearTablas();
		
		// se ingresa al super usuario
		$_POST['root_pass'] = password_hash($_POST['root_pass'], PASSWORD_BCRYPT, array('cost' => 11));
		
		$modWizard->setAdminUser($_POST['root_email']);
		$modWizard->setAdminPass($_POST['root_pass']);
		$modWizard->registrarSuperusuario();
		
		
		// se encriptan los datos necesarios
		$_POST['tokenUser'] = password_hash($_POST['tokenUser'], PASSWORD_BCRYPT, array('cost' => 11));
		$_POST['tokenPass'] = password_hash($_POST['tokenPass'], PASSWORD_BCRYPT, array('cost' => 11));
		$_POST['apiUser'] = password_hash($_POST['apiUser'], PASSWORD_BCRYPT, array('cost' => 11));
		$_POST['apiPass'] = password_hash($_POST['apiPass'], PASSWORD_BCRYPT, array('cost' => 11));
		
		// se recupera el texto del archivo template de configuracion
		$confTemplate = file_get_contents($pathT);
		
		// se reemplazan los valores del template por los del formulario de instalación
		$confTemplate = str_replace('[[urlfriendly]]', $_POST['urlfriendly'], $confTemplate);
		$confTemplate = str_replace('[[locale]]', $_POST['locale'], $confTemplate);
		$confTemplate = str_replace('[[timezone]]', $_POST['timezone'], $confTemplate);
		$confTemplate = str_replace('[[passCript]]', $_POST['passCript'], $confTemplate);
		$confTemplate = str_replace('[[db_host]]', $_POST['db_host'], $confTemplate);
		$confTemplate = str_replace('[[db_base]]', $_POST['db_base'], $confTemplate);
		$confTemplate = str_replace('[[db_user]]', $_POST['db_user'], $confTemplate);
		$confTemplate = str_replace('[[db_pass]]', $_POST['db_pass'], $confTemplate);
		$confTemplate = str_replace('[[tokenVida]]', $_POST['tokenVida'], $confTemplate);
		$confTemplate = str_replace('[[tokenUser]]', $_POST['tokenUser'], $confTemplate);
		$confTemplate = str_replace('[[tokenPass]]', $_POST['tokenPass'], $confTemplate);
		$confTemplate = str_replace('[[apiUser]]', $_POST['apiUser'], $confTemplate);
		$confTemplate = str_replace('[[apiPass]]', $_POST['apiPass'], $confTemplate);
		$confTemplate = str_replace('[[email_smtp]]', $_POST['email_smtp'], $confTemplate);
		$confTemplate = str_replace('[[email_user]]', $_POST['email_user'], $confTemplate);
		$confTemplate = str_replace('[[email_pass]]', $_POST['email_pass'], $confTemplate);
		$confTemplate = str_replace('[[email_port]]', $_POST['email_port'], $confTemplate);
		$confTemplate = str_replace('[[email_ssl]]', $_POST['email_ssl'], $confTemplate);
		$confTemplate = str_replace('[[email_fromEmail]]', $_POST['email_fromEmail'], $confTemplate);
		$confTemplate = str_replace('[[email_fromNombre]]', $_POST['email_fromNombre'], $confTemplate);
		
		// se recupera el texto del archivo de configuracion original
		$confOrig = file_get_contents($pathO);
		
		// se incluye la configuración
		$confOrig = str_replace('/*[[configuracion]]*/', $confTemplate, $confOrig);
		
		// se sobrescribe el archivo de configuración original
		$fp = fopen($pathO, 'w');
		fwrite($fp, $confOrig);
		fclose($fp);
		
		if ($_POST['htaccess'] == 1) {
			
			// se crea el fichero .htaccess
			$txtHtaccess  = 'Options -Indexes' . "\n";
			$txtHtaccess .= '<IfModule mod_rewrite.c>' . "\n";
			$txtHtaccess .= '    RewriteEngine on' . "\n";
			$txtHtaccess .= '    RewriteCond $1 !^(index\.php|public|robots\.txt)' . "\n";
			$txtHtaccess .= '    RewriteRule ^(.*)$ ' . $_SERVER['PHP_SELF'] . '?/$1 [L]' . "\n";
			$txtHtaccess .= '</IfModule>';

			$fp = fopen($pathA, 'w');
			fwrite($fp, $txtHtaccess);
			fclose($fp);
		
		}
		
		// se finaliza el script
		echo json_encode(array('estado' =>'ok', 'url'=> $_POST['urlfriendly']));
		
	}
	
}
