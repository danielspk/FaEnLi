<?php

$app->register('conex', function () use ($app) {
    return \DMS\PHPLibs\DataBase::conectar($app->config('db'));
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

$app->register('smtpTransport', function () use ($app) {

    $confEmail = $app->config('email');

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

$app->register('smtpMailer', function () use ($app) {
    return \Swift_Mailer::newInstance($app->container('smtpTransport'));
});

$app->register('smtpMessage', function () {
    return \Swift_Message::newInstance();
});