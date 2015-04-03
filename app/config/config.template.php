<?php

/* Constantes */
define('URLFRIENDLY', '[[urlfriendly]]');

/* Configuración de TornadoPHP */
$app->config('tornado_environment_development', true);
$app->config('tornado_hmvc_use', false);
$app->config('tornado_hmvc_module_path', 'app/modules');
$app->config('tornado_hmvc_serialize_path', 'app/modules');

/* Variables de Configuración */
$app->config('locale', '[[locale]]');
$app->config('timezone', '[[timezone]]');
$app->config('passCript', '[[passCript]]');
$app->config('theme', '[[theme]]');

$app->config('db', array(
	'motor'		=> 'MYSQL',
	'host'      => '[[db_host]]',
	'base'      => '[[db_base]]',
	'user'      => '[[db_user]]',
	'pass'      => '[[db_pass]]',
	'collation' => 'utf8'
));

$app->config('api', array(
	'tokenVida' => '[[tokenVida]]',
	'tokenUser'	=> '[[tokenUser]]',
	'tokenPass' => '[[tokenPass]]',
	'apiUser'	=> '[[apiUser]]',
	'apiPass'	=> '[[apiPass]]'
));
		
$app->config('email', array(
	'smtp'		=> '[[email_smtp]]',
	'user'      => '[[email_user]]',
	'pass'      => '[[email_pass]]',
	'port'      => '[[email_port]]',
	'ssl'		=> '[[email_ssl]]',
	'fromEmail' => '[[email_fromEmail]]',
	'fromNombre'=> '[[email_fromNombre]]',
));

