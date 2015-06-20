<?php

// se incluye la librería CaptchaDMS
require('../../app/vendor/danielspk/phplibs/captcha.php');

// se instancia la clase CaptchaDMS
$captcha = new \DMS\PHPLibs\Captcha();

// se configura el captcha
$captcha->puntos = 0;
$captcha->lineas = 0;

// se solicita la creación de la imagen
$captcha->generarImagen();
