<?php
namespace App\Modules\Publico\Controller;

use DMS\Tornado\Controller;
use App\Modules\Publico\Model\Usuario as UsuarioMod;
use App\Modules\Publico\Model\Comprobante as ComprobanteMod;

class Acceso extends Controller
{
	
	public function logout()
	{
		session_start();
		session_unset();
		session_destroy();
		
		header('Location: ' . URLFRIENDLY . 'login');
		exit();
	}
	
    public function login()
    {
		$this->validaSesionIniciada();

        $this->app->render('app/modules/Publico/View/login.tpl.php', ['app' => $this->app]);
    }
	
	public function procesarLogin()
	{

		$email    = filter_input(INPUT_POST, 'txtEmail', FILTER_SANITIZE_STRING);
		$password = filter_input(INPUT_POST, 'txtPassword', FILTER_SANITIZE_STRING);
		$codigo   = filter_input(INPUT_POST, 'txtCodigo', FILTER_SANITIZE_STRING);
		
		$respuesta['estado'] = 'error';
		
		if ($email === null || $password === null || $codigo === null) {
			$respuesta['descripcion'] = 'Solicitud no válida';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		$captcha = $this->app->container('captcha');
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			$this->imprimeJSON($respuesta);
            return null;
		}

		$usuario = new UsuarioMod();
		$usuario->setEmail($email);
		
		$usrDatos = $usuario->recuperarDatos();
		
		if ($usrDatos === false || password_verify($password, $usrDatos->clave) === false) {
			$respuesta['descripcion'] = 'Los datos suministrados no son válidos';
			$this->imprimeJSON($respuesta);
            return null;
		}

		$_SESSION['iniciada'] = true;
		$_SESSION['usrNombre'] = $usrDatos->nombre;
		$_SESSION['usrApellido'] = $usrDatos->apellido;
		$_SESSION['usrEmail'] = $usrDatos->email;
		$_SESSION['usrRoot'] = $usrDatos->root;
		
		$respuesta['estado'] = 'ok';
		$respuesta['url'] = URLFRIENDLY . 'panel';
		$this->imprimeJSON($respuesta);
		
	}
	
	public function recupero()
	{
		$this->validaSesionIniciada();

        $this->app->render('app/modules/Publico/View/recupero.tpl.php', ['app' => $this->app]);
	}
	
	public function procesarRecupero()
	{
		$email  = filter_input(INPUT_POST, 'txtEmail', FILTER_SANITIZE_STRING);
		$codigo = filter_input(INPUT_POST, 'txtCodigo', FILTER_SANITIZE_STRING);
		
		$respuesta['estado'] = 'error';
		
		if ($email === null || $codigo === null) {
			
			$respuesta['descripcion'] = 'Solicitud no válida';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		$captcha = $this->app->container('captcha');
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			$this->imprimeJSON($respuesta);
            return null;
		}

		$usuario = new UsuarioMod();
		$usuario->setEmail($email);
		
		$usrDatos = $usuario->recuperarDatos();
		
		if ($usrDatos === false) {
			$respuesta['descripcion'] = 'Los datos suministrados no son válidos';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		// se genera y guarda el código de recupero de contraseña
		$cripto = $this->app->container('cripto');
	
		$hash = $cripto->crearHash(98);
		
		$usuario->setResetCodigo($hash);
		$usuario->setResetVida(time() + (60*60*24*1));
		$usuario->grabarLinkRecupero();
		
		// se recupera el texto de la plantilla del mail de recupero y se
		// reemplazan los atributos por defecto
		$txtEmail = file_get_contents(__DIR__ . '/../../../config/templates/email-recupero-clave.html');
		
		$txtEmail = str_replace('[[usuario]]', $usrDatos->nombre . ', ' . $usrDatos->apellido, $txtEmail);
		$txtEmail = str_replace('[[url]]', URLFRIENDLY . 'restablecer/' . $hash, $txtEmail);
		
		// se remite un mail al cliente
		$confEmail = $this->app->config('email');

        $sMailer = $this->app->container('smtpMailer');
        $sMessage = $this->app->container('smtpMessage');
		$sMessage->setSubject('Restrablecer constraseña')
			->setFrom(array($confEmail['fromEmail'] => $confEmail['fromNombre']))
			->setTo(array($usrDatos->email))
			->setBody($txtEmail, 'text/html');
		
		$sMailer->send($sMessage);
		
		// se finaliza exitosamente la acción
		$respuesta['estado'] = 'ok';
		$respuesta['url'] = URLFRIENDLY . 'login';
		$this->imprimeJSON($respuesta);
	}
	
	public function restablecer($pCodigoRes = null)
	{
		$this->validaSesionIniciada();

        $this->app->render('app/modules/Publico/View/restablecer.tpl.php', ['app' => $this->app, 'codigoRes' => $pCodigoRes]);
	}
	
	public function procesarRestablecer()
	{
		$email     = filter_input(INPUT_POST, 'txtEmail', FILTER_SANITIZE_STRING);
		$password  = filter_input(INPUT_POST, 'txtPassword', FILTER_SANITIZE_STRING);
		$password2 = filter_input(INPUT_POST, 'txtPassword2', FILTER_SANITIZE_STRING);
		$codigo    = filter_input(INPUT_POST, 'txtCodigo', FILTER_SANITIZE_STRING);
		$codigoRes = filter_input(INPUT_POST, 'txtCodigoRes', FILTER_SANITIZE_STRING);
		
		$respuesta['estado'] = 'error';
		
		if ($email === null || $password === null || $password2 === null || $codigo === null || $codigoRes === null) {
			$respuesta['descripcion'] = 'Solicitud no válida';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		$captcha = $this->app->container('captcha');
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		$usuario = new UsuarioMod();
		$usuario->setEmail($email);
		$usuario->setResetCodigo($codigoRes);
		$usuario->setResetVida(time());
	
		if ($usuario->validarCodigoRestablecimiento() !== true) {
			$respuesta['descripcion'] = 'Datos no válidos';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		if ($password !== $password2) {
			$respuesta['descripcion'] = 'Las contraseñas no son iguales';
			$this->imprimeJSON($respuesta);
            return null;
		}

		// se valida el formaro de las contraseñas
		$valid = $this->app->container('valida');
		
		if (!$valid->contrasenia($password)) {
			$respuesta['descripcion'] = 'La contraseña ingresada no es válida';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		// se actualiza la contraseña
		$usuario->setClave($password);
		$usuario->actualizarClave();
		
		// se finaliza exitosamente
		$respuesta['estado'] = 'ok';
		$respuesta['url'] = URLFRIENDLY . 'login';
		$this->imprimeJSON($respuesta);
	}
	
	public function registro()
	{
		$this->validaSesionIniciada();

        $this->app->render('app/modules/Publico/View/registro.tpl.php', ['app' => $this->app]);
	}
	
	public function procesarRegistro()
	{
		$nombre      = filter_input(INPUT_POST, 'txtNombre', FILTER_SANITIZE_STRING);
		$apellido    = filter_input(INPUT_POST, 'txtApellido', FILTER_SANITIZE_STRING);
		$email       = filter_input(INPUT_POST, 'txtEmail', FILTER_SANITIZE_STRING);
		$password    = filter_input(INPUT_POST, 'txtPassword', FILTER_SANITIZE_STRING);
		$password2   = filter_input(INPUT_POST, 'txtPassword2', FILTER_SANITIZE_STRING);
		$codigo      = filter_input(INPUT_POST, 'txtCodigo', FILTER_SANITIZE_STRING);
		$terminosuso = filter_input(INPUT_POST, 'chkLicencia', FILTER_SANITIZE_NUMBER_INT);
		
		$respuesta['estado'] = 'error';
		
		if ($nombre === null || $apellido === null || $email === null || $password === null || $password2 === null || $codigo === null) {
			$respuesta['descripcion'] = 'Solicitud no válida';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		$captcha = $this->app->container('captcha');
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			$this->imprimeJSON($respuesta);
            return null;
		}

		$usuario = new UsuarioMod();
		$usuario->setEmail($email);
		
		$usrDatos = $usuario->recuperarDatos();
		
		if ($usrDatos !== false) {
			$respuesta['descripcion'] = 'El usuario se encuentra registrado';
			$this->imprimeJSON($respuesta);
            return null;
		}

		$comprobante = new ComprobanteMod();
		$comprobante->setEmail($email);
		
		if ($comprobante->recuperarCantidadComprobantes() == 0) {
			$respuesta['descripcion'] = 'No dispone de compras. No es posible registrarse';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		// se valida el formato de los campos ingresados
		$valid = $this->app->container('valida');
		
		if (!$valid->nombreApellido($nombre)) {
			$respuesta['descripcion'] = 'El nombre no es válido';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		if (!$valid->nombreApellido($apellido)) {
			$respuesta['descripcion'] = 'El apellido no es válido';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		if (!$valid->email($email)) {
			$respuesta['descripcion'] = 'La dirección de email no es válida';
			$this->imprimeJSON($respuesta);
            return null;
		}

		if ($password !== $password2) {
			$respuesta['descripcion'] = 'Las contraseñas no son iguales';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		if (!$valid->contrasenia($password)) {
			$respuesta['descripcion'] = 'La contraseña ingresada no es válida';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		if ($terminosuso != 1) {
			$respuesta['descripcion'] = 'Debe aceptarlos términos y condiciones de uso';
			$this->imprimeJSON($respuesta);
            return null;
		}
		
		// se ingresa el usuario
		$usuario->setNombre($nombre);
		$usuario->setApellido($apellido);
		$usuario->setClave($password);
		$usuario->grabarRegistro();
		
		// se finaliza exitosamente
		$respuesta['estado'] = 'ok';
		$respuesta['url'] = URLFRIENDLY . 'login';
		$this->imprimeJSON($respuesta);
	}
	
	public function terminos()
	{
		$txtTerminos = file_get_contents(__DIR__ . '/../../../config/templates/licencia-de-uso.html');

        $this->app->render('app/modules/Publico/View/terminos.tpl.php', ['app' => $this->app, 'terminos'=>$txtTerminos]);
	}
	
	private function validaSesionIniciada()
	{
		session_start();
		
		if (isset($_SESSION['iniciada']) && $_SESSION['iniciada'] === true) {
			header('Location: ' . URLFRIENDLY . 'panel');
			exit();
		}
	}

	private function imprimeJSON($json)
	{
		echo json_encode($json);
	}
	
}
