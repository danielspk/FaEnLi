<?php

$app->register('conex', function () use ($app) {
    return \DMS\PHPLibs\DataBase::conectar($app->config('db'));
});

