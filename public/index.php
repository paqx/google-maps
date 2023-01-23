<?php

define('CONFIG_FILE', dirname(__DIR__, 1).'/config.php');
define('ENV_FILE', dirname(__DIR__, 1).'/.env');

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Controller\MapController;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', [MapController::class, 'show']);

$app->post('/', [MapController::class, 'store']);

$app->run();
