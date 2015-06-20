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
$app->config('passCript', 'pepepeoekfhyaahhandnfndtfhf');
$app->config('theme', 'celeste');

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
	'tokenUser'	=> '$2y$11$OrUs66FfbyR/e91tl1Z2VOxhAMhScz95I62SG/hPxESRz6squVQBS',
	'tokenPass' => '$2y$11$oS5G0FR2gYixtBfimAnOMeeXXBs0MS2O..4XrS69S95lmWscr6T6S',
	'apiUser'	=> '$2y$11$yDsNgP4r9vJvXPtll4FxU.eKendJHaWnUnewwvT9sSd0uusUt62s.',
	'apiPass'	=> '$2y$11$E1.YbuEYu5SgT6QgMpx8oe6OyJeV3k0PAq.kDB.CQM6z6Dtfl0otq'
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
