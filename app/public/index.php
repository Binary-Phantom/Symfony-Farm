<?php

use App\Kernel;

// 🔑 DESABILITA CARREGAMENTO DE .env GLOBALMENTE
putenv('SYMFONY_DOTENV_VARS=');

require_once dirname(__DIR__).'/vendor/autoload.php';

$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] ?? 'prod';
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] ?? false;

$kernel = new Kernel(
    $_SERVER['APP_ENV'],
    (bool) $_SERVER['APP_DEBUG']
);

$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);