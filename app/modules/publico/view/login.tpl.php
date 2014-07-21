<!DOCTYPE HTML>
<html lang="en-ES">
<head>
	<meta charset="UTF-8">
	<base href='<?=URLFRIENDLY?>' />
	<title> Bienvenido</title>
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
			<img src="./public/img/logo_company.png" alt="Su Logo Empresarial" />
		</div>
		
		<div class="frmAcceso">
			
			<div class="frmAccesoSup">
				
				<h4>Inicie sesión para comenzar o <a href="./registro">regístrese</a></h4>
				
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
						<label for="txtCodigo"><i class="fa fa-eye"></i></label>
						<input type="text" name="txtCodigo" id="txtCodigo" placeholder="Código Captcha" />
					</div>
					
					<div class="centrado">
						<img id="imgCaptcha" class="imgCaptcha" src="./public/img/captcha.php" alt="Captcha" />
						<i class="icoRefresh fa fa-refresh giro180"></i>
					</div>
					
				</form>
				
			</div>
			
			<div class="frmAccesoInf flotar-clear">
				
				<div class="flotar-izquierda">
					<a href="./recupero">¿Olvido su Contraseña?</a>
				</div>
				
				<div class="flotar-derecha">
					<a id="btnLogin" href="#" data-ajax="./login" class="boton"><i class="fa fa-lock"></i>Ingresar</a>
				</div>
				
			</div>
			
		</div>
		
	</div>
	
	<?php $this->loadView('publico|includes/footer'); ?>
	
	<script type="text/javascript" src="./public/js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="./public/js/comun.js"></script>
	<script type="text/javascript" src="./public/js/login.js"></script>
	
</body>
</html>