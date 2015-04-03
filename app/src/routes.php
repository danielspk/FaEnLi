<?php

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

$app->route('POST   /api/token',					'api|token|getToken');
$app->route('POST   /api/logs',						'api|log|getLogs');
$app->route('DELETE /api/logs/borrar',				'api|log|borrarLogs');
$app->route('POST   /api/usuarios',					'api|usuario|getUsuarios');
$app->route('POST   /api/comprobantes/registrar',	'api|comprobante|registrar');
$app->route('DELETE /api/comprobantes/borrar',		'api|comprobante|borrar');
