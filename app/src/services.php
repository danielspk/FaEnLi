<?php
use DMS\Tornado\Service;

$app->register('conex.config', $app->config('db'));
$app->register('conex', function (Service $c) {
    return \DMS\PHPLibs\DataBase::conectar($c->get('conex.config'));
});

$app->register('cripto', function () {
    return new \DMS\PHPLibs\Cripto();
});

$app->register('valida', function () {
    return new \DMS\PHPLibs\Valida();
});

$app->register('captcha', function () {
    return new \DMS\PHPLibs\Captcha();
});

$app->register('smtpTransport.config', $app->config('email'));
$app->register('smtpTransport', function (Service $c) {

    $confEmail = $c->get('smtpTransport.config');

    if ($confEmail['ssl'] == true) {
        return \Swift_SmtpTransport::newInstance($confEmail['smtp'], $confEmail['port'], 'ssl')
            ->setUsername($confEmail['user'])
            ->setPassword($confEmail['pass']);
    } else {
        return \Swift_SmtpTransport::newInstance($confEmail['smtp'], $confEmail['port'])
            ->setUsername($confEmail['user'])
            ->setPassword($confEmail['pass']);
    }
});

$app->register('smtpMailer', function (Service $c) {
    return \Swift_Mailer::newInstance($c->get('smtpTransport'));
});

$app->register('smtpMessage', function () {
    return \Swift_Message::newInstance();
});