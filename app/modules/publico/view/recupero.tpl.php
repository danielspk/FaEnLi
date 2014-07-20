<!DOCTYPE HTML>
<html lang="en-ES">
<head>
	<meta charset="UTF-8">
	<base href='<?=URLFRIENDLY?>' />
	<title> Recupero de contraseña</title>
	<link rel="shortcut icon" type="image/x-icon" href="./public/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="./public/css/font-awesome.min.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./public/css/estilos.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./public/css/themes/celeste.css" media="all" />
</head>
<body class="bgAcceso">
	
	<?php $this->loadView('publico|includes/notificaciones'); ?>
	
	<?php $this->loadView('publico|includes/slogan'); ?>
	
	<div class="pnlAcceso">
		
		<div class="logoAcceso">
			<img src="./public/img/logo_generic.png" alt="Su Logo Empresarial" />
		</div>
		
		<div class="frmAcceso">
			
			<div class="frmAccesoSup">
				
				<h4>Recupero de contraseña</h4>
				
				<form action="" method="post">
					
					<div class="form-element">
						<label for="txtEmail"><i class="fa fa-envelope"></i></label>
						<input type="text" name="txtEmail" id="txtEmail" placeholder="Dirección de Email" />
					</div>
					
					<div class="form-element">
						<label for="txtCodigo"><i class="fa fa-eye"></i></label>
						<input type="text" name="txtCodigo" id="txtCodigo" placeholder="Código Captcha" />
					</div>
					
					<div class="centrado">
						<img id="imgCaptcha" class="imgCaptcha" src="./public/img/captcha.php" alt="Captcha" />
						<i class="icoRefresh fa fa-refresh giro180" onclick="document.getElementById('imgCaptcha').src='./public/img/captcha.php?'+Math.random()"></i>
					</div>
					
				</form>
				
			</div>
			
			<div class="frmAccesoInf flotar-clear">
				
				<div class="flotar-izquierda">
					<a href="./login" class="boton boton-opaco"><i class="fa fa-arrow-left"></i>Volver</a>
				</div>
				
				<div class="flotar-derecha">
					<a id="btnEnviar" href="#" data-ajax="./recupero" class="boton"><i class="fa fa-envelope-o"></i>Enviar</a>
				</div>
				
			</div>
			
		</div>
		
	</div>
	
	<?php $this->loadView('publico|includes/footer'); ?>
	
	<script type="text/javascript" src="./public/js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="./public/js/comun.js"></script>
	<script type="text/javascript" src="./public/js/recupero.js"></script>
	
</body>
</html>