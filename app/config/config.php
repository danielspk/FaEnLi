<?php

/* Constantes */
define('URLFRIENDLY', 'http://local-dsp.web/faenli/');

/* Variables de Configuración */
$app = DMS\Tornado\Tornado::getInstance();

$app->config('locale', 'spanish');
$app->config('timezone', 'America/Argentina/Buenos_Aires');

$app->config('db', array(
	'motor'		=> 'MYSQL',
	'host'      => 'localhost',
	'base'      => 'faenli',
	'user'      => 'faenli',
	'pass'      => '1234',
	'collation' => 'utf8'
));

$app->config('email', array(
	'smtp'		=> 'smtp.gmail.com',
	'user'      => 'daniel.speery@gmail.com',
	'pass'      => 'derionsee27',
	'port'      => '465',
	'ssl'		=> true,
	'fromEmail' => 'no-reply@laempresa.com',
	'fromNombre'=> 'Restrablecer Acceso',
));

/* Autoload de librerías */
$app->autoload(true);
$app->autoload("DMS\Libs", array('vendor/dms-libs'));