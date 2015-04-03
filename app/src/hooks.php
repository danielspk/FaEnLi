<?php

$app->hook('init', function () use ($app) {

    // se setea la configuración de zona y hora
    setlocale(LC_TIME, $app->config('locale'));
    date_default_timezone_set($app->config('timezone'));

	// se determina si se debe iniciar el instalador del sistema
	if (URLFRIENDLY === '[[urlfriendly]]') {
		$app->forwardModule('instalacion|wizard|instalar');
		exit();
	}

});

$app->hook('404', function () {
	
	// se determina si se debe mostrar una vista html o json
	$valida = new \DMS\PHPLibs\Valida();
	
	if ($valida->esAjax()) {
		echo 'Destino 404';
	} else {
		require __DIR__ . '/../modules/publico/view/404.tpl.php';
	}
	
});

$app->hook('error', function () use ($app) {
	
	// se genera un hash del error
	$cripto = new \DMS\PHPLibs\Cripto();
	$hash = date('YmdHis') . $cripto->crearHash(6);
	
	// se guarda error en un archivo de log
	error_log('#' . $hash . ":\n" . $app->error() . "\n\n", 3, __DIR__ . '/../logs/log.log');
	
	// se determina si se debe mostrar una vista html o json
	$valida = new \DMS\PHPLibs\Valida();
	
	if ($valida->esAjax()) {
		echo 'Código: #' . $hash;
	} else {
		require __DIR__ . '/../modules/publico/view/error.tpl.php';
	}

});
