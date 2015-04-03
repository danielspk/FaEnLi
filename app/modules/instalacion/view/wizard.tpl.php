<!DOCTYPE HTML>
<html lang="en-ES">
<head>
	<meta charset="UTF-8">
	<base href='<?= str_replace('index.php', '', $_SERVER['PHP_SELF']) ?>' />
	<title> Instalación FaEnLi</title>
	<link rel="shortcut icon" type="image/x-icon" href="./public/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="./public/css/font-awesome.min.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./public/css/estilos.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./public/css/themes/celeste.css" media="all" />
</head>
<body class="bgAcceso">
	
	<?php $this->loadView('publico|includes/notificaciones'); ?>
	
	<div class="pnlInstalacion">
		
		<div class="centrado">
			<img src="./public/img/logo_faenli.png" alt="FaEnLi" />
		</div>
					
		<div>

			<h2>Instalación del Sistema:</h2>

			<form action="#" method="post">

				<h3>Super Usuario:</h3>
				
				<div class="form-element">
					<label class="conTexto" for="root_email"><i class="fa fa-envelope"></i>Email:</label>
					<input type="text" name="root_email" id="root_email" placeholder="Email" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="root_pass"><i class="fa fa-key"></i>Clave:</label>
					<input type="password" name="root_pass" id="root_pass" placeholder="Clave" />
				</div>
				
				<h3>Aplicación:</h3>
				
				<div class="form-element">
					<label class="conTexto" for="urlfriendly"><i class="fa fa-cloud"></i>URL:</label>
					<input type="text" name="urlfriendly" id="urlfriendly" placeholder="URL" />
				</div>

				<div class="form-element">
					<label class="conTexto" for="htaccess"><i class="fa fa-thumbs-up"></i>URL Amigables:</label>
					<select name="htaccess" id="htaccess">
						<option value="1">SI</option>
						<option value="0">NO</option>
					</select>
				</div>

				<div class="form-element">
					<label class="conTexto" for="theme"><i class="fa fa-thumbs-up"></i>Theme:</label>
					<select name="theme" id="theme">
						<option value="celeste">celeste</option>
						<option value="rojo">rojo</option>
						<option value="verde">verde</option>
						<option value="violeta">violeta</option>
					</select>
				</div>
				
				<h3>Configuración Regional:</h3>
				
				<div class="form-element">
					<label class="conTexto" for="locale"><i class="fa fa-language"></i>Locale:</label>
					<input type="text" name="locale" id="locale" placeholder="Locale" value="spanish" />
				</div>

				<div class="form-element">
					<label class="conTexto" for="timezone"><i class="fa fa-globe"></i>TimeZone:</label>
					<input type="text" name="timezone" id="timezone" placeholder="TimeZone" value="America/Argentina/Buenos_Aires" />
				</div>
				
				<h3>Seguridad / API:</h3>
				
				<div class="form-element">
					<label class="conTexto" for="passCript"><i class="fa fa-key"></i>Clave de Encriptado:</label>
					<input type="password" name="passCript" id="passCript" placeholder="Clave de Encriptado" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="tokenVida"><i class="fa fa-clock-o"></i>Vida del Token (segs):</label>
					<select name="tokenVida" id="tokenVida">
						<?php for ($i=15; $i<60; $i++) { ?>
						<option value="<?=$i?>"><?=$i?></option>
						<?php }?>
					</select>
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="tokenUser"><i class="fa fa-user"></i>Usuario del Token:</label>
					<input type="text" name="tokenUser" id="tokenUser" placeholder="Usuario del Token" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="tokenPass"><i class="fa fa-key"></i>Clave del Token:</label>
					<input type="password" name="tokenPass" id="tokenPass" placeholder="Clave del Token" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="apiUser"><i class="fa fa-user"></i>Usuario del API:</label>
					<input type="text" name="apiUser" id="apiUser" placeholder="Usuario del API" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="apiPass"><i class="fa fa-key"></i>Clave del API:</label>
					<input type="password" name="apiPass" id="apiPass" placeholder="Clave del API" />
				</div>
				
				<h3>Base de Datos:</h3>

                <div class="form-element">
                    <label class="conTexto" for="db_create"><i class="fa fa-road"></i>Crear tablas:</label>
                    <select name="db_create" id="db_create">
                        <option value="1">SI</option>
                        <option value="0">NO</option>
                    </select>
                </div>

				<div class="form-element">
					<label class="conTexto" for="db_host"><i class="fa fa-sitemap"></i>Host / IP:</label>
					<input type="text" name="db_host" id="db_host" placeholder="Host / IP" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="db_base"><i class="fa fa-database"></i>Base de Datos:</label>
					<input type="text" name="db_base" id="db_base" placeholder="Base de Datos" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="db_user"><i class="fa fa-user"></i>Usuario:</label>
					<input type="text" name="db_user" id="db_user" placeholder="Usuario" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="db_pass"><i class="fa fa-key"></i>Clave:</label>
					<input type="password" name="db_pass" id="db_pass" placeholder="Clave" />
				</div>
				
				<h3>Correo Electrónico:</h3>
				
				<div class="form-element">
					<label class="conTexto" for="email_smtp"><i class="fa fa-envelope-o"></i>Host SMTP:</label>
					<input type="text" name="email_smtp" id="email_smtp" placeholder="Host STMP" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="email_port"><i class="fa fa-slack"></i>N° de Puerto:</label>
					<input type="text" name="email_port" id="email_port" placeholder="N° de Puerto" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="email_ssl"><i class="fa fa-key"></i>Usar SSL:</label>
					<select name="email_ssl" id="email_ssl">
						<option value="true">SI</option>
						<option value="false">NO</option>
					</select>
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="email_user"><i class="fa fa-user"></i>Usuario:</label>
					<input type="text" name="email_user" id="email_user" placeholder="Usuario" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="email_pass"><i class="fa fa-key"></i>Clave:</label>
					<input type="password" name="email_pass" id="email_pass" placeholder="Clave" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="email_fromEmail"><i class="fa fa-building"></i>Dirección de Remitente:</label>
					<input type="text" name="email_fromEmail" id="email_fromEmail" placeholder="Dirección de Remitente" />
				</div>
				
				<div class="form-element">
					<label class="conTexto" for="email_fromNombre"><i class="fa fa-male"></i>Nombre del Remitente:</label>
					<input type="text" name="email_fromNombre" id="email_fromNombre" placeholder="Nombre del Remitente:" />
				</div>
				
				<input type="hidden" name="btnInstalar" value="1" />
				
			</form>

		</div>

		<div class="pnlBotonesIns flotar-clear">
			
			<div class="flotar-derecha">
				<a id="btnInstalar" href="#" data-ajax="./" class="boton"><i class="fa fa-cubes"></i>Instalar</a>
			</div>

		</div>

	</div>
	
	<?php $this->loadView('publico|includes/footer'); ?>
	
	<script type="text/javascript" src="./public/js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="./public/js/comun.js"></script>
	<script type="text/javascript" src="./public/js/instalacion.js"></script>
	
</body>
</html>