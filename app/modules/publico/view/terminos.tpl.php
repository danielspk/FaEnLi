<!DOCTYPE HTML>
<html lang="en-ES">
<head>
	<meta charset="UTF-8">
    <base href='<?php echo URLFRIENDLY ?>' />
	<title> Terminos de uso</title>
	<link rel="shortcut icon" type="image/x-icon" href="./public/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="./public/css/estilos.css" media="all" />
    <?php $app->render('app/modules/Publico/View/includes/theme.tpl.php'); ?>
</head>
<body class="bgAcceso">
	
	<div class="licencia-de-uso">
		
		<header>
			<img src="./public/img/logo_company.png" alt="Su Logo Empresarial" />
		</header>
		
		<div>
			<?php echo $terminos ?>
		</div>
		
		<footer>
			<a href="./registro">Ir al registro de usuario</a>
		</footer>
		
	</div>

    <?php $app->render('app/modules/Publico/View/includes/footer.tpl.php'); ?>
	
</body>
</html>