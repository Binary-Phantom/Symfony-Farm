<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/.env')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

/**
 */
$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] ?? 'prod';
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] ?? false;

/**
 * Kernel depois do env carregado
 */
$kernel = new Kernel(
    $_SERVER['APP_ENV'],
    (bool) $_SERVER['APP_DEBUG']
);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);