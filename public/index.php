<?php

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use TypeForge\Core\ContentResolver;
use TypeForge\Exceptions\TypeNotFoundException;
use Slim\Factory\AppFactory;
use DI\Container;
use TypeForge\Controller\ContentController;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->get('/api/content', [ContentController::class, 'getContent']);
$app->post('/api/content', [ContentController::class, 'createContent']);


$app->run();
