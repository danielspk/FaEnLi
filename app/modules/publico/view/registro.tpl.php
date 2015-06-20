<!DOCTYPE HTML>
<html lang="en-ES">
<head>
	<meta charset="UTF-8">
    <base href='<?php echo URLFRIENDLY ?>' />
	<title> Registro de nuevo usuario</title>
	<link rel="shortcut icon" type="image/x-icon" href="./public/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="./public/css/font-awesome.min.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./public/css/estilos.css" media="all" />
    <?php $app->render('app/modules/Publico/View/includes/theme.tpl.php'); ?>
</head>
<body class="bgAcceso">

    <?php $app->render('app/modules/Publico/View/includes/notificaciones.tpl.php'); ?>

    <?php $app->render('app/modules/Publico/View/includes/slogan.tpl.php'); ?>

	<div class="pnlAcceso">
		
		<div class="logoAcceso">
			<img src="./public/img/logo_company.png" alt="Su Logo Empresarial" />
		</div>
		
		<div class="frmAcceso">
			
			<div class="frmAccesoSup">
				
				<h4>Registro de Nuevo Usuario</h4>
				
				<form action="#" method="post">
					
					<div class="form-element">
						<label for="txtNombre"><i class="fa fa-user"></i></label>
						<input type="text" name="txtNombre" id="txtNombre" placeholder="Nombre" />
					</div>
					
					<div class="form-element">
						<label for="txtApellido"><i class="fa fa-user"></i></label>
						<input type="text" name="txtApellido" id="txtApellido" placeholder="Apellido" />
					</div>
					
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
					</div>
					
					<div class="centrado">
						<img id="imgCaptcha" class="imgCaptcha" src="./public/img/captcha.php" alt="Captcha" />
						<i class="icoRefresh fa fa-refresh giro180" onclick="document.getElementById('imgCaptcha').src='./public/img/captcha.php?'+Math.random()"></i>
					</div>
					
					<div class="pad-top-10 centrado">
						<input type="checkbox" name="chkLicencia" id="chkLicencia" value="1" />
						Acepto los <a href="./terminos-de-uso" target="_blank">Términos y Condiciones</a>
					</div>
					
				</form>
				
			</div>
			
			<div class="frmAccesoInf flotar-clear">
				
				<div class="flotar-izquierda">
					<a href="./login" class="boton boton-opaco"><i class="fa fa-arrow-left"></i>Volver</a>
				</div>
				
				<div class="flotar-derecha">
					<a id="btnRegistrarse" href="#" data-ajax="./registro" class="boton"><i class="fa fa-user"></i>Registrarse</a>
				</div>
				
			</div>
			
		</div>
		
	</div>

    <?php $app->render('app/modules/Publico/View/includes/footer.tpl.php'); ?>
	
	<script type="text/javascript" src="./public/js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="./public/js/comun.js"></script>
	<script type="text/javascript" src="./public/js/registro.js"></script>
	
</body>
</html>