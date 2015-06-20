<?php
namespace App\Modules\Instalacion\Controller;

use DMS\Tornado\Controller;
use App\Modules\Instalacion\Model\Wizard as WizardMod;

class Wizard extends Controller
{

	public function instalar()
	{
		if (isset($_POST['btnInstalar'])) {
			$this->procesarInstalacion();
			return;
		}

        $this->app->render('app/modules/Instalacion/View/wizard.tpl.php', ['app' => $this->app]);
	}

	private function procesarInstalacion()
	{
		// rutas de archivos de configuración
		$pathC = __DIR__ . '/../../../config/config.php';
		$pathH = __DIR__ . '/../../../../.htaccess';
		
		// se crea la estructura de la base de datos
		$modWizard = new WizardMod(array(
			'motor'		=> 'MYSQL',
			'host'      => $_POST['db_host'],
			'base'      => $_POST['db_base'],
			'user'      => $_POST['db_user'],
			'pass'      => $_POST['db_pass'],
			'collation' => 'utf8'
		));

        // si se selecciono generar estructura de tablas ......
		$modWizard->crearTablas();
		
		// se ingresa al super usuario en la base de datos
		$_POST['root_pass'] = password_hash($_POST['root_pass'], PASSWORD_BCRYPT, array('cost' => 11));
		
		$modWizard->setAdminUser($_POST['root_email']);
		$modWizard->setAdminPass($_POST['root_pass']);
		$modWizard->registrarSuperusuario();
		
		// se encriptan los datos de la API
		$_POST['tokenUser'] = password_hash($_POST['tokenUser'], PASSWORD_BCRYPT, array('cost' => 11));
		$_POST['tokenPass'] = password_hash($_POST['tokenPass'], PASSWORD_BCRYPT, array('cost' => 11));
		$_POST['apiUser']   = password_hash($_POST['apiUser'], PASSWORD_BCRYPT, array('cost' => 11));
		$_POST['apiPass']   = password_hash($_POST['apiPass'], PASSWORD_BCRYPT, array('cost' => 11));
		
		// se recupera el texto del archivo de configuracion
		// y se reemplazan los valores por default por los de la instalación
		$txtConfig = file_get_contents($pathC);
		
		$txtConfig = str_replace('[[urlfriendly]]', $_POST['urlfriendly'], $txtConfig);
		$txtConfig = str_replace('[[theme]]', $_POST['theme'], $txtConfig);
		$txtConfig = str_replace('[[locale]]', $_POST['locale'], $txtConfig);
		$txtConfig = str_replace('[[timezone]]', $_POST['timezone'], $txtConfig);
		$txtConfig = str_replace('[[passCript]]', $_POST['passCript'], $txtConfig);
		$txtConfig = str_replace('[[db_host]]', $_POST['db_host'], $txtConfig);
		$txtConfig = str_replace('[[db_base]]', $_POST['db_base'], $txtConfig);
		$txtConfig = str_replace('[[db_user]]', $_POST['db_user'], $txtConfig);
		$txtConfig = str_replace('[[db_pass]]', $_POST['db_pass'], $txtConfig);
		$txtConfig = str_replace('\'[[tokenVida]]\'', $_POST['tokenVida'], $txtConfig);
		$txtConfig = str_replace('[[tokenUser]]', $_POST['tokenUser'], $txtConfig);
		$txtConfig = str_replace('[[tokenPass]]', $_POST['tokenPass'], $txtConfig);
		$txtConfig = str_replace('[[apiUser]]', $_POST['apiUser'], $txtConfig);
		$txtConfig = str_replace('[[apiPass]]', $_POST['apiPass'], $txtConfig);
		$txtConfig = str_replace('[[email_smtp]]', $_POST['email_smtp'], $txtConfig);
		$txtConfig = str_replace('[[email_user]]', $_POST['email_user'], $txtConfig);
		$txtConfig = str_replace('[[email_pass]]', $_POST['email_pass'], $txtConfig);
		$txtConfig = str_replace('[[email_port]]', $_POST['email_port'], $txtConfig);
		$txtConfig = str_replace('\'[[email_ssl]]\'', $_POST['email_ssl'], $txtConfig);
		$txtConfig = str_replace('[[email_fromEmail]]', $_POST['email_fromEmail'], $txtConfig);
		$txtConfig = str_replace('[[email_fromNombre]]', $_POST['email_fromNombre'], $txtConfig);
		
		// se sobrescribe el archivo de configuración
		$fp = fopen($pathC, 'w');
		fwrite($fp, $txtConfig);
		fclose($fp);
		
		// se crea el fichero .htaccess
		if ($_POST['htaccess'] == 1) {
			
			$txtHtaccess  = 'Options -Indexes' . "\n";
			$txtHtaccess .= '<IfModule mod_rewrite.c>' . "\n";
			$txtHtaccess .= '    RewriteEngine on' . "\n";
			$txtHtaccess .= '    RewriteCond $1 !^(index\.php|public|robots\.txt)' . "\n";
			$txtHtaccess .= '    RewriteRule ^(.*)$ ' . $_SERVER['PHP_SELF'] . '?/$1 [L]' . "\n";
			$txtHtaccess .= '</IfModule>';

			$fp = fopen($pathH, 'w');
			fwrite($fp, $txtHtaccess);
			fclose($fp);
		
		}
		
		// se finaliza el script
		echo json_encode(array('estado' =>'ok', 'url'=> $_POST['urlfriendly']));
	}
	
}
