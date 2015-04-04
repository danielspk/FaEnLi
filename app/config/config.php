<?php

/* Constantes */
define('URLFRIENDLY', 'http://dsp.dev/faenli/');

/* Configuración de TornadoPHP */
$app->config('tornado_environment_development', true);
$app->config('tornado_hmvc_use', false);
$app->config('tornado_hmvc_module_path', 'app/modules');
$app->config('tornado_hmvc_serialize_path', 'app/modules');

/* Configuración de FaEnLi */
$app->config('locale', 'spanish');
$app->config('timezone', 'America/Argentina/Buenos_Aires');
$app->config('passCript', '1234');
$app->config('theme', 'rojo');

$app->config('db', array(
	'motor'		=> 'MYSQL',
	'host'      => 'localhost',
	'base'      => 'faenli',
	'user'      => 'root',
	'pass'      => 'root',
	'collation' => 'utf8'
));

$app->config('api', array(
	'tokenVida' => 15,
	'tokenUser'	=> '$2y$11$MJY7LscPuWyR/ew51nac8uy060RZHaYYRtAaUDlbiItkgcT1N8u9W',
	'tokenPass' => '$2y$11$FchfAfq56aUvRIuC3TFmvuuMf2lLJMkhLx8dCNNGcs0RSYfXXjrim',
	'apiUser'	=> '$2y$11$OLR8RoUjglXaLdXT3fD/beNv4mA7Mfg6EcBFEz1zahDP4ISdQDxPq',
	'apiPass'	=> '$2y$11$9qrcWQSOlWAXDJCXTM1cR.KXcmby03r/ivFJSVkmFmFUuGx0g1xhq'
));

$app->config('email', array(
	'smtp'		 => '',
	'user'       => '',
	'pass'       => '',
	'port'       => '',
	'ssl'		 => true,
	'fromEmail'  => '',
	'fromNombre' => '',
));
