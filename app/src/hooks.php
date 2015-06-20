<?php

$app->hook('init', function () use ($app) {

    if (URLFRIENDLY !== '[[urlfriendly]]') {
        // se setea la configuración de zona y hora
        setlocale(LC_TIME, $app->config('locale'));
        date_default_timezone_set($app->config('timezone'));
    }

});

$app->hook('before', function () use ($app) {

    // se determina si se debe iniciar el instalador del sistema
    if (URLFRIENDLY === '[[urlfriendly]]') {
        $app->forwardModule('instalacion|wizard|instalar');
        return false;
    }

});

$app->hook('404', function () use ($app) {
	
	// se determina si se debe mostrar una vista html o json
	$valida = $app->container('valida');
	
	if ($valida->esAjax()) {
		echo 'Destino 404';
	} else {
        $this->app->render('app/modules/Publico/View/404.tpl.php', ['app' => $this->app]);
	}
	
});

$app->hook('error', function () use ($app) {
	
	// se genera un hash del error
	$cripto = $app->container('cripto');
	$hash = date('YmdHis') . $cripto->crearHash(6);
	
	// se guarda error en un archivo de log
	error_log('#' . $hash . ":\n" . $app->error() . "\n\n", 3, __DIR__ . '/../logs/log.log');
	
	// se determina si se debe mostrar una vista html o json
	$valida = $app->container('valida');
	
	if ($valida->esAjax()) {
		echo 'Código: #' . $hash;
	} else {
        $this->app->render('app/modules/Publico/View/error.tpl.php', ['app' => $this->app]);
	}

});
