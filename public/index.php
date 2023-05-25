<?php

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
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
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(function (
  Request $request,
  Throwable $exception,
  bool $displayErrorDetails,
  bool $logErrors,
  bool $logErrorDetails
) use ($app) {
  $payload = ['error' => $exception->getMessage()];

  $response = $app->getResponseFactory()->createResponse();
  $response->getBody()->write(
    json_encode($payload)
  );

  return $response
    ->withStatus($exception->getCode())
    ->withHeader("Content-Type", "application/json");
});

// $contentController = new ContentController($container);

$app->get('/api/content', [ContentController::class, 'getContent']);
$app->post('/api/content', [ContentController::class, 'createContent']);


$app->run();
