<!DOCTYPE HTML>
<html lang="en-ES">
<head>
	<meta charset="UTF-8">
    <base href='<?php echo URLFRIENDLY ?>' />
	<title> Error detectado</title>
	<link rel="shortcut icon" type="image/x-icon" href="./public/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="./public/css/font-awesome.min.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./public/css/estilos.css" media="all" />
</head>
<body class="bgAcceso">
	
	<div class="errGeneral">
		<img src="./public/img/error.png" alt="Error General" />
		<span>Si el error persiste consulte al administrador del sistema (Código: <?= $hash; ?>)</span>
		<a href="./">Volver al inicio</a>
	</div>

    <?php $app->render('app/modules/Publico/View/includes/footer.tpl.php'); ?>
	
</body>
</html>