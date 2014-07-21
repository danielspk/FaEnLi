<!DOCTYPE HTML>
<html lang="en-ES">
<head>
	<meta charset="UTF-8">
	<base href='<?=URLFRIENDLY?>' />
	<title> Restalecer contraseña de usuario</title>
	<link rel="shortcut icon" type="image/x-icon" href="./public/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="./public/css/font-awesome.min.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./public/css/estilos.css" media="all" />
	<?php $this->loadView('publico|includes/theme'); ?>
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
				
				<h3>Restablecimiento de contraseña</h3>
				
				<form action="#" method="post">
					
					<div class="form-element">
						<label for="txtEmail"><i class="fa fa-envelope"></i></label>
						<input type="text" name="txtEmail" id="txtEmail" placeholder="Dirección de Email" />
					</div>
					
					<div class="form-element">
						<label for="txtPassword"><i class="fa fa-key"></i></label>
						<input type="password" name="txtPassword" id="txtPassword" placeholder="Contraseña" />
					</div>
					
					<div class="form-element">
						<label for="txtPassword2"><i class="fa fa-key"></i></label>
						<input type="password" name="txtPassword2" id="txtPassword2" placeholder="Repetir Contraseña" />
					</div>
					
					<div class="form-element">
						<label for="txtCodigo"><i class="fa fa-eye"></i></label>
						<input type="text" name="txtCodigo" id="txtCodigo" placeholder="Código Captcha" />
						<input type="hidden" name="txtCodigoRes" id="txtCodigoRes" value="<?= $codigoRes; ?>" />
					</div>
					
					<div class="centrado">
						<img id="imgCaptcha" class="imgCaptcha" src="./public/img/captcha.php" alt="Captcha" />
						<i class="icoRefresh fa fa-refresh giro180"></i>
					</div>
					
				</form>
				
			</div>
			
			<div class="frmAccesoInf flotar-clear">
				
				<div class="flotar-izquierda">
					<a href="./login" class="boton boton-opaco"><i class="fa fa-arrow-left"></i>Volver</a>
				</div>
				
				<div class="flotar-derecha">
					<a id="btnRestablecer" href="#" data-ajax="./restablecer" class="boton"><i class="fa fa-retweet"></i>Restablecer</a>
				</div>
				
			</div>
			
		</div>
		
	</div>
	
	<?php $this->loadView('publico|includes/footer'); ?>
	
	<script type="text/javascript" src="./public/js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="./public/js/comun.js"></script>
	<script type="text/javascript" src="./public/js/restablecer.js"></script>
	
</body>
</html>