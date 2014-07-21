<?php

/* Instancia de Tornado */
$app = DMS\Tornado\Tornado::getInstance();

/*[[configuracion]]*/

/* Autoload de librerías */
$app->autoload(true);
$app->autoload("DMS\Libs", array('vendor/dms-libs'));
