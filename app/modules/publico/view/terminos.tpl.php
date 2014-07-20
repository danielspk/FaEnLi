<!DOCTYPE HTML>
<html lang="en-ES">
<head>
	<meta charset="UTF-8">
	<base href='<?=URLFRIENDLY?>' />
	<title> Terminos de uso</title>
	<link rel="shortcut icon" type="image/x-icon" href="./public/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="./public/css/estilos.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./public/css/themes/celeste.css" media="all" />
</head>
<body class="bgAcceso">
	
	<div class="licencia-de-uso">
		
		<header>
			<img src="./public/img/logo_generic.png" alt="Su Logo Empresarial" />
		</header>
		
		<div>
			<?=$terminos;?>
		</div>
		
		<footer>
			<a href="./registro">Ir al registro de usuario</a>
		</footer>
		
	</div>
	
	<?php $this->loadView('publico|includes/footer'); ?>
	
</body>
</html>