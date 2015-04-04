<?php
namespace app\modules\publico\controller;

use DMS\Tornado\Controller;
use app\modules\publico\model as Modelo;

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
		$this->_validaSesionIniciada();
		
		$this->loadView('publico|login');
    }
	
	public function procesarLogin()
	{

		$email = filter_input(INPUT_POST, 'txtEmail', FILTER_SANITIZE_STRING);
		$password = filter_input(INPUT_POST, 'txtPassword', FILTER_SANITIZE_STRING);
		$codigo = filter_input(INPUT_POST, 'txtCodigo', FILTER_SANITIZE_STRING);
		
		$respuesta['estado'] = 'error';
		
		if ($email === null || $password === null || $codigo === null) {
			
			$respuesta['descripcion'] = 'Solicitud no válida';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		$captcha = $this->app->container('captcha');
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		$this->loadModel('publico|usuario');
		
		$usuario = new Modelo\Usuario();
		$usuario->setEmail($email);
		
		$usrDatos = $usuario->recuperarDatos();
		
		if ($usrDatos === false || password_verify($password, $usrDatos->clave) === false) {
			$respuesta['descripcion'] = 'Los datos suministrados no son válidos';
			$this->_imprimeJSON($respuesta);
            return null;
		}

		$_SESSION['iniciada'] = true;
		$_SESSION['usrNombre'] = $usrDatos->nombre;
		$_SESSION['usrApellido'] = $usrDatos->apellido;
		$_SESSION['usrEmail'] = $usrDatos->email;
		$_SESSION['usrRoot'] = $usrDatos->root;
		
		$respuesta['estado'] = 'ok';
		$respuesta['url'] = URLFRIENDLY . 'panel';
		$this->_imprimeJSON($respuesta);
		
	}
	
	public function recupero()
	{

		$this->_validaSesionIniciada();
		
		$this->loadView('publico|recupero');
	}
	
	public function procesarRecupero()
	{
		
		$email = filter_input(INPUT_POST, 'txtEmail', FILTER_SANITIZE_STRING);
		$codigo = filter_input(INPUT_POST, 'txtCodigo', FILTER_SANITIZE_STRING);
		
		$respuesta['estado'] = 'error';
		
		if ($email === null || $codigo === null) {
			
			$respuesta['descripcion'] = 'Solicitud no válida';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		$captcha = $this->app->container('captcha');
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		$this->loadModel('publico|usuario');

		$usuario = new Modelo\Usuario();
		$usuario->setEmail($email);
		
		$usrDatos = $usuario->recuperarDatos();
		
		if ($usrDatos === false) {
			$respuesta['descripcion'] = 'Los datos suministrados no son válidos';
			$this->_imprimeJSON($respuesta);
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
		$this->_imprimeJSON($respuesta);
	}
	
	public function restablecer($pCodigoRes = null)
	{
		$this->_validaSesionIniciada();
		
		$this->loadView('publico|restablecer', array('codigoRes' => $pCodigoRes));
	}
	
	public function procesarRestablecer()
	{
	
		$email = filter_input(INPUT_POST, 'txtEmail', FILTER_SANITIZE_STRING);
		$password = filter_input(INPUT_POST, 'txtPassword', FILTER_SANITIZE_STRING);
		$password2 = filter_input(INPUT_POST, 'txtPassword2', FILTER_SANITIZE_STRING);
		$codigo = filter_input(INPUT_POST, 'txtCodigo', FILTER_SANITIZE_STRING);
		$codigoRes = filter_input(INPUT_POST, 'txtCodigoRes', FILTER_SANITIZE_STRING);
		
		$respuesta['estado'] = 'error';
		
		if ($email === null || $password === null || $password2 === null || $codigo === null || $codigoRes === null) {
			
			$respuesta['descripcion'] = 'Solicitud no válida';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		$captcha = $this->app->container('captcha');
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			$this->_imprimeJSON($respuesta);
            return null;
		}

		$this->loadModel('publico|usuario');
		
		$usuario = new Modelo\Usuario();
		$usuario->setEmail($email);
		$usuario->setResetCodigo($codigoRes);
		$usuario->setResetVida(time());
	
		if ($usuario->validarCodigoRestablecimiento() !== true) {
			$respuesta['descripcion'] = 'Datos no válidos';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		if ($password !== $password2) {
			$respuesta['descripcion'] = 'Las contraseñas no son iguales';
			$this->_imprimeJSON($respuesta);
            return null;
		}

		// se valida el formaro de las contraseñas
		$valid = $this->app->container('valida');
		
		if (!$valid->contrasenia($password)) {
			$respuesta['descripcion'] = 'La contraseña ingresada no es válida';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		// se actualiza la contraseña
		$usuario->setClave($password);
		$usuario->actualizarClave();
		
		// se finaliza exitosamente
		$respuesta['estado'] = 'ok';
		$respuesta['url'] = URLFRIENDLY . 'login';
		$this->_imprimeJSON($respuesta);
		
	}
	
	public function registro()
	{
		$this->_validaSesionIniciada();
		
		$this->loadView('publico|registro');
	}
	
	public function procesarRegistro()
	{

		$nombre = filter_input(INPUT_POST, 'txtNombre', FILTER_SANITIZE_STRING);
		$apellido = filter_input(INPUT_POST, 'txtApellido', FILTER_SANITIZE_STRING);
		$email = filter_input(INPUT_POST, 'txtEmail', FILTER_SANITIZE_STRING);
		$password = filter_input(INPUT_POST, 'txtPassword', FILTER_SANITIZE_STRING);
		$password2 = filter_input(INPUT_POST, 'txtPassword2', FILTER_SANITIZE_STRING);
		$codigo = filter_input(INPUT_POST, 'txtCodigo', FILTER_SANITIZE_STRING);
		$terminosuso = filter_input(INPUT_POST, 'chkLicencia', FILTER_SANITIZE_NUMBER_INT);
		
		$respuesta['estado'] = 'error';
		
		if ($nombre === null || $apellido === null || $email === null || $password === null || $password2 === null || $codigo === null) {
			
			$respuesta['descripcion'] = 'Solicitud no válida';
			$this->_imprimeJSON($respuesta);
            return null;
			
		}
		
		$captcha = $this->app->container('captcha');
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			$this->_imprimeJSON($respuesta);
            return null;
		}

		$this->loadModel('publico|usuario');
		
		$usuario = new Modelo\Usuario();
		$usuario->setEmail($email);
		
		$usrDatos = $usuario->recuperarDatos();
		
		if ($usrDatos !== false) {
			$respuesta['descripcion'] = 'El usuario se encuentra registrado';
			$this->_imprimeJSON($respuesta);
            return null;
		}

		$this->loadModel('publico|comprobante');
		
		$comprobante = new Modelo\Comprobante();
		$comprobante->setEmail($email);
		
		if ($comprobante->recuperarCantidadComprobantes() == 0) {
			$respuesta['descripcion'] = 'No dispone de compras. No es posible registrarse';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		// se valida el formato de los campos ingresados
		$valid = $this->app->container('valida');
		
		if (!$valid->nombreApellido($nombre)) {
			$respuesta['descripcion'] = 'El nombre no es válido';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		if (!$valid->nombreApellido($apellido)) {
			$respuesta['descripcion'] = 'El apellido no es válido';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		if (!$valid->email($email)) {
			$respuesta['descripcion'] = 'La dirección de email no es válida';
			$this->_imprimeJSON($respuesta);
            return null;
		}

		if ($password !== $password2) {
			$respuesta['descripcion'] = 'Las contraseñas no son iguales';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		if (!$valid->contrasenia($password)) {
			$respuesta['descripcion'] = 'La contraseña ingresada no es válida';
			$this->_imprimeJSON($respuesta);
            return null;
		}
		
		if ($terminosuso != 1) {
			$respuesta['descripcion'] = 'Debe aceptarlos términos y condiciones de uso';
			$this->_imprimeJSON($respuesta);
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
		$this->_imprimeJSON($respuesta);
		
	}
	
	public function terminos()
	{
		$txtTerminos = file_get_contents(__DIR__ . '/../../../config/templates/licencia-de-uso.html');
		
		$this->loadView('publico|terminos', array('terminos'=>$txtTerminos));
	}
	
	private function _validaSesionIniciada()
	{
		session_start();
		
		if (isset($_SESSION['iniciada']) && $_SESSION['iniciada'] === true) {
			header('Location: ' . URLFRIENDLY . 'panel');
			exit();
		}
	}

    /*
	private function _validaSesionExistente()
	{
		session_start();
		
		if (!isset($_SESSION['iniciada']) || $_SESSION['iniciada'] !== true) {
			header('Location: ' . URLFRIENDLY . 'login');
			exit();
		}
	}
	*/

	private function _imprimeJSON($json)
	{
		echo json_encode($json);
	}
	
}
