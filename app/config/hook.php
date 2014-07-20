<?php

$app = DMS\Tornado\Tornado::getInstance();

$app->hook('init', function () {

    ob_start(function ($pBuffer, $pPhase) {
        $buffer = mb_output_handler($pBuffer, $pPhase);
        $buffer = ob_gzhandler($pBuffer, $pPhase);

        return $buffer;
    });

	setlocale(LC_TIME, 'spanish');
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	
});

$app->hook('end', function () {
    ob_end_flush();
});

$app->hook('404', function () {
	// se carga la vista de error 404
	require __DIR__ . '/../modules/publico/view/404.tpl.php';
});

$app->hook('error', function () use ($app) {
	
	// se genera un hash del error
	$cripto = new \DMS\Libs\Cripto();
	$hash = date('YmdHis') . $cripto->crearHash(6);
	
	// se guarda error en un archivo de log
	error_log('#' . $hash . ":\n" . $app->error() . "\n\n", 3, __DIR__ . '/../log/log.log');
	
	// se carga la vista de error general
	require __DIR__ . '/../modules/publico/view/error.tpl.php';
});
