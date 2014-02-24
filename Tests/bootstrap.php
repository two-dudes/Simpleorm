<?php

use Models\Mapper\Pdo\PdoMapper;

error_reporting(E_ALL);
ini_set('display_errors', 'on');

spl_autoload_register(function($file) {
    $appDir = dirname(realpath(__FILE__)) . '/../';
    $fileName = str_replace('\\', '/', $file) . '.php';
    $fileName = ltrim($fileName, '/');
    $fileName = $appDir . $fileName;

    if (file_exists($fileName)) {
        require_once $fileName;
    }
});

PdoMapper::setConnectionConfig(array(
    'user' => 'root',
    'password' => '123123',
    'host' => '127.0.0.1',
    'port' => '3306',
    'db'   => 'simpleorm'
));