<?php

$app = DMS\Tornado\Tornado::getInstance();

$app->hook('init', function () use ($app) {

	// se comprime el contenido del buffer
    ob_start(function ($pBuffer, $pPhase) {
        $buffer = mb_output_handler($pBuffer, $pPhase);
        $buffer = ob_gzhandler($pBuffer, $pPhase);

        return $buffer;
    });

	// se setea la configuración de zona y hora
	setlocale(LC_TIME, $app->config('locale'));
	date_default_timezone_set($app->config('timezone'));
	
});

$app->hook('end', function () {
	// se vuelca el buffer
    ob_end_flush();
});

$app->hook('404', function () {
	
	// se determina si se debe mostrar una vista html o json
	$valida = new \DMS\Libs\Valida();
	
	if ($valida->esAjax()) {
		echo 'Destino 404';
	} else {
		require __DIR__ . '/../modules/publico/view/404.tpl.php';
	}
	
});

$app->hook('error', function () use ($app) {
	
	// se genera un hash del error
	$cripto = new \DMS\Libs\Cripto();
	$hash = date('YmdHis') . $cripto->crearHash(6);
	
	// se guarda error en un archivo de log
	error_log('#' . $hash . ":\n" . $app->error() . "\n\n", 3, __DIR__ . '/../log/log.log');
	
	// se determina si se debe mostrar una vista html o json
	$valida = new \DMS\Libs\Valida();
	
	if ($valida->esAjax()) {
		echo 'Código: #' . $hash;
	} else {
		require __DIR__ . '/../modules/publico/view/error.tpl.php';
	}

});
