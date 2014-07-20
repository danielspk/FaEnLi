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

$app->config('api', array(
	'tokenVida' => 45,
	'tokenUser'	=> '$2y$11$Tc6iC.zDzsEE14btZ7UHvuHMl6/Vhv66KOORO4bdb9MpedE7lHKd2',
	'tokenPass' => '$2y$11$7vSDE26rtZ16e.TTZXZ3KOShIT1Ba.T1De8WF7t0xC9WbciWFqFOW',
	'apiUser'	=> '$2y$11$F4e5qY0nmdyOj.z0DkQBouu2sKVa3ZOiMN0OzM4XigsLWnQvy2CN2',
	'apiPass'	=> '$2y$11$OaYv5p.Br4L0M1vOd9Xf7OlFKGx54Oey4Yg32LVlqGwT73X22blDe'
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