<?php

// se incluye la librería CaptchaDMS
require('../../app/vendor/dms-libs/captcha.php');

// se instancia la clase CaptchaDMS
$captcha = new \DMS\Libs\Captcha();

// se configura el captcha
$captcha->puntos = 0;
$captcha->lineas = 0;

// se solicita la creación de la imagen
$captcha->generarImagen();
