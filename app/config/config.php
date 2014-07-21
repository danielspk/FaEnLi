<?php

/* Instancia de Tornado */
$app = DMS\Tornado\Tornado::getInstance();

/* Constantes */
define('URLFRIENDLY', 'http://local-dsp.web/faenli/');

/* Variables de Configuración */
$app->config('locale', 'spanish');
$app->config('timezone', 'America/Argentina/Buenos_Aires');
$app->config('passCript', '2siR7cGop2wa7B');
$app->config('theme', 'violeta');

$app->config('db', array(
	'motor'		=> 'MYSQL',
	'host'      => 'localhost',
	'base'      => 'faenli',
	'user'      => 'faenli',
	'pass'      => '1234',
	'collation' => 'utf8'
));

$app->config('api', array(
	'tokenVida' => 45,
	'tokenUser'	=> '$2y$11$AFgfjjLf86hE3AEuOZE1W.MsO8wN40NVcDziBQm/vuZo/Ld5FuSVa',
	'tokenPass' => '$2y$11$oIFrmwd.uPtXFII.8HPFLezH38a4r2aVqbm22K10BGrlLbKVe1hxC',
	'apiUser'	=> '$2y$11$YbpFtZkITOdKDoDYRtbA0uOy0HuCFQsGid586K.jRuqvj94.hvRT2',
	'apiPass'	=> '$2y$11$ayuNFQjcaa/czSUKhLFOfOz9hd7fRxjAVwKlLzTvrjRctecC.UV.O'
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
