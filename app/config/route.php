<?php

/* Entutamientos de la aplicación */
$app = DMS\Tornado\Tornado::getInstance();

$app->route('/',						'publico|acceso|login');
$app->route('GET  /login',				'publico|acceso|login');
$app->route('POST /login',				'publico|acceso|procesarLogin');
$app->route('GET  /recupero',			'publico|acceso|recupero');
$app->route('POST /recupero',			'publico|acceso|procesarRecupero');
$app->route('GET  /restablecer/:alpha',	'publico|acceso|restablecer');
$app->route('POST /restablecer',		'publico|acceso|procesarRestablecer');
$app->route('GET  /registro',			'publico|acceso|registro');
$app->route('POST /registro',			'publico|acceso|procesarRegistro');
$app->route('GET  /terminos-de-uso',	'publico|acceso|terminos');
$app->route('/logout',					'publico|acceso|logout');
$app->route('/panel',					'publico|comprobante|panel');
$app->route('/comprobante/:alpha',		'publico|comprobante|descarga');
