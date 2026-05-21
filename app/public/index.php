<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require_once dirname(__DIR__).'/vendor/autoload.php';

// 🔑 CARREGA .env APENAS EM DESENVOLVIMENTO
$env = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'dev';
if ($env !== 'prod' && is_file(dirname(__DIR__).'/.env')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

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