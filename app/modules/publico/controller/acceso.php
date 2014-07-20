<?php
namespace app\modules\publico\controller;

use app\modules\publico\model as modelo;

class Acceso extends \DMS\Tornado\Controller
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
			return $this->_imprimeJSON($respuesta);
			
		}
		
		$captcha = new \DMS\Libs\Captcha();
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			return $this->_imprimeJSON($respuesta);
		}
		
		$this->loadModel('publico|usuario');
		
		$usuario = new modelo\Usuario();
		$usuario->setEmail($email);
		
		$usrDatos = $usuario->recuperarDatos();
		
		if ($usrDatos === false || password_verify($password, $usrDatos->clave) === false) {
			$respuesta['descripcion'] = 'Los datos suministrados no son válidos';
			return $this->_imprimeJSON($respuesta);
		}

		$_SESSION['iniciada'] = true;
		$_SESSION['usrNombre'] = $usrDatos->nombre;
		$_SESSION['usrApellido'] = $usrDatos->apellido;
		$_SESSION['usrEmail'] = $usrDatos->email;
		
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
			return $this->_imprimeJSON($respuesta);
			
		}
		
		$captcha = new \DMS\Libs\Captcha();
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			return $this->_imprimeJSON($respuesta);
		}
		
		$this->loadModel('publico|usuario');

		$usuario = new modelo\Usuario();
		$usuario->setEmail($email);
		
		$usrDatos = $usuario->recuperarDatos();
		
		if ($usrDatos === false) {
			$respuesta['descripcion'] = 'Los datos suministrados no son válidos';
			return $this->_imprimeJSON($respuesta);
		}
		
		// se genera y guarda el código de recupero de contraseña
		$cripto = new \DMS\Libs\Cripto();
	
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
		require __DIR__ . '/../../../vendor/swiftmailer/swift_required.php';
		
		$confEmail = \DMS\Tornado\Tornado::getInstance()->config('email');
		
		if ($confEmail['ssl'] == true) {
			
			$sTransport = \Swift_SmtpTransport::newInstance($confEmail['smtp'], $confEmail['port'], 'ssl')
			->setUsername($confEmail['user'])
			->setPassword($confEmail['pass']);
			
		} else {
			
			$sTransport = \Swift_SmtpTransport::newInstance($confEmail['smtp'], $confEmail['port'])
			->setUsername($confEmail['user'])
			->setPassword($confEmail['pass']);
			
		}
		
		$sMailer = \Swift_Mailer::newInstance($sTransport);
		
		$sMessage = \Swift_Message::newInstance('Restrablecer constraseña')
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
			return $this->_imprimeJSON($respuesta);
			
		}
		
		$captcha = new \DMS\Libs\Captcha();
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			return $this->_imprimeJSON($respuesta);
		}

		$this->loadModel('publico|usuario');
		
		$usuario = new modelo\Usuario();
		$usuario->setEmail($email);
		$usuario->setResetCodigo($codigoRes);
		$usuario->setResetVida(time());
	
		if ($usuario->validarCodigoRestablecimiento() !== true) {
			$respuesta['descripcion'] = 'Datos no válidos';
			return $this->_imprimeJSON($respuesta);
		}
		
		if ($password !== $password2) {
			$respuesta['descripcion'] = 'Las contraseñas no son iguales';
			return $this->_imprimeJSON($respuesta);
		}

		// se valida el formaro de las contraseñas
		$valid = new \DMS\Libs\Valida();
		
		if (!$valid->contrasenia($password)) {
			$respuesta['descripcion'] = 'La contraseña ingresada no es válida';
			return $this->_imprimeJSON($respuesta);
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
			return $this->_imprimeJSON($respuesta);
			
		}
		
		$captcha = new \DMS\Libs\Captcha();
		
		if ($captcha->validarCaptcha($codigo) === false) {
			$respuesta['descripcion'] = 'El código captcha no es correcto';
			return $this->_imprimeJSON($respuesta);
		}

		$this->loadModel('publico|usuario');
		
		$usuario = new modelo\Usuario();
		$usuario->setEmail($email);
		
		$usrDatos = $usuario->recuperarDatos();
		
		if ($usrDatos !== false) {
			$respuesta['descripcion'] = 'El usuario se encuentra registrado';
			return $this->_imprimeJSON($respuesta);
		}

		$this->loadModel('publico|comprobante');
		
		$comprobante = new modelo\Comprobante();
		$comprobante->setEmail($email);
		
		if ($comprobante->recuperarCantidadComprobantes() == 0) {
			$respuesta['descripcion'] = 'No dispone de compras. No es posible registrarse';
			return $this->_imprimeJSON($respuesta);
		}
		
		// se valida el formato de los campos ingresados
		$valid = new \DMS\Libs\Valida();
		
		if (!$valid->nombreApellido($nombre)) {
			$respuesta['descripcion'] = 'El nombre no es válido';
			return $this->_imprimeJSON($respuesta);
		}
		
		if (!$valid->nombreApellido($apellido)) {
			$respuesta['descripcion'] = 'El apellido no es válido';
			return $this->_imprimeJSON($respuesta);
		}
		
		if (!$valid->email($email)) {
			$respuesta['descripcion'] = 'La dirección de email no es válida';
			return $this->_imprimeJSON($respuesta);
		}

		if ($password !== $password2) {
			$respuesta['descripcion'] = 'Las contraseñas no son iguales';
			return $this->_imprimeJSON($respuesta);
		}
		
		if (!$valid->contrasenia($password)) {
			$respuesta['descripcion'] = 'La contraseña ingresada no es válida';
			return $this->_imprimeJSON($respuesta);
		}
		
		if ($terminosuso != 1) {
			$respuesta['descripcion'] = 'Debe aceptarlos términos y condiciones de uso';
			return $this->_imprimeJSON($respuesta);
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
	
	private function _validaSesionExistente()
	{
		session_start();
		
		if (!isset($_SESSION['iniciada']) || $_SESSION['iniciada'] !== true) {
			header('Location: ' . URLFRIENDLY . 'login');
			exit();
		}
	}
	
	private function _imprimeJSON($json)
	{
		echo json_encode($json);
	}
	
}
