<!DOCTYPE HTML>
<html lang="en-ES">
<head>
	<meta charset="UTF-8">
    <base href='<?php echo URLFRIENDLY ?>' />
	<title> Sus Comprobantes</title>
	<link rel="shortcut icon" type="image/x-icon" href="./public/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="./public/css/font-awesome.min.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./public/css/estilos.css" media="all" />
    <?php $app->render('app/modules/Publico/View/includes/theme.tpl.php'); ?>
</head>
<body class="bgPanel">
	
	<div class="pnlHeader">
		
		<div class="txtUser"><?php echo $nombre . ' ' . $apellido . ' &lt;' . $email . '&gt;' ?></div>
		
		<div class="btnLogout">
			<a href="./logout">Cerrar Sesión<i class="fa fa-power-off"></i></a>
		</div>
		
	</div>
	
	<div class="pnlLogo">
		<img src="./public/img/logo_company.png" alt="Su Logo Empresarial" />
	</div>
	
	<div class="pnlFacturas">
		
		<div class="pnlFacturasSup">
			<h4>Sus Comprobantes:</h4>
		</div>
		
		<div class="pnlFacturasInf">
			
			<table class="grillaDatos" cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<th class="ordenAsc">Fecha</th>
						<th class="orden">Tipo</th>
						<th class="orden">Número</th>
						<th class="orden">Moneda</th>
						<th class="orden">Importe</th>
						<th class="noOrden centrado" style="width:110px">Descarga</th>
					</tr>
				</thead>
				<tbody>
					<?php $cant = 0; ?>
					<?php foreach($comprobantes as $row) : ?>
					<?php $cant++; ?>
					<tr <?php echo ($cant % 2 == 0) ? ' class="trPar"' : '' ?>>
						<td><?php echo $row->fecha ?></td>
						<td><?php echo $row->tipo ?></td>
						<td><?php echo str_pad($row->ptoventa, 4, '0', STR_PAD_LEFT) . '-' . str_pad($row->numero, 8, '0', STR_PAD_LEFT) ?></td>
						<td><?php echo $row->moneda ?></td>
						<td>$ <?php echo number_format($row->importe, 2, '.', ',') ?></td>
						<td class="centrado">
							<a href="./comprobante/<?php echo $row->archivo ?>" target="_blank">
								<img src="./public/img/pdf.png" alt="Descarga" />
							</a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			
			<div class="pagFacturas flotar-clear">
				
				<div class="flotar-izquierda">Mostrando <?= count($comprobantes); ?> de <?= count($comprobantes); ?> comprobantes emitidos</div>
				
				<div class="flotar-derecha paginado">
					<a href="#" class="pag-anterior pag-desactivado">&nbsp;<i class="fa fa-arrow-left"></i>Anterior</a>
					<a href="#">1</a>
					<a href="#">2</a>
					<a href="#" class="pag-activo">3</a>
					<a href="#">4</a>
					<a href="#" class="pag-siguiente">Siguiente<i class="fa fa-arrow-right"></i>&nbsp;</a>
				</div>
				
			</div>
			
		</div>
		
	</div>
	
	<footer class="powerBy">
		<a href="https://github.com/danielspk/FaEnLi">FaEnLi - OpenSource</a>
	</footer>
	
</body>
</html>