<?php

namespace TypeForge\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use TypeForge\Core\ContentResolver;
use TypeForge\Exceptions\TypeNotFoundException;
use TypeForge\Exceptions\InvalidFieldsException;
use TypeForge\Exceptions\MissingFieldsException;

class ContentController
{
  protected $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function getContent(Request $request, Response $response)
  {
    $query = $request->getQueryParams();
    $type = isset($query['type']) ? $query['type'] : null;
    $id = isset($query['id']) ? $query['id'] : null;

    // if no type is given, return 404 error
    if (!$type) {
      $json = json_encode(['error' => 'Type not found']);

      $response->getBody()->write($json);
      return $response
        ->withStatus(404)
        ->withHeader('Content-Type', 'application/json');
    }

    try {
      $contentResolver = new ContentResolver($type);

      // if id is given, return single item, otherwise return all items
      $data = $id ? $contentResolver->getById($id) : $contentResolver->getAll();

      $json = json_encode(
        [
          'type' => $contentResolver->getType(),
          'data' => $data
        ]
      );
      $response->getBody()->write($json);

      return $response->withHeader('Content-Type', 'application/json');
    } catch (TypeNotFoundException $e) {
      $json = json_encode(['error' => $e->getMessage()]);

      $response->getBody()->write($json);
      return $response
        ->withStatus(404)
        ->withHeader('Content-Type', 'application/json');
    }
  }

  public function createContent(Request $request, Response $response)
  {
    $data = $request->getParsedBody();

    $type = isset($data['type']) ? $data['type'] : null;
    $data = isset($data['data']) ? $data['data'] : null;

    // if no type is given, return 404 error
    if (!$type) {
      $json = json_encode(['error' => 'Type not found']);

      $response->getBody()->write($json);
      return $response
        ->withStatus(404)
        ->withHeader('Content-Type', 'application/json');
    }


    // if data is empty return 422 error
    if (!$data) {
      $json = json_encode(['error' => 'Invalid data']);

      $response->getBody()->write($json);
      return $response
        ->withStatus(422)
        ->withHeader('Content-Type', 'application/json');
    }

    try {
      // create item
      $contentResolver = new ContentResolver($type);
      $item = $contentResolver->create($data);

      // if item was created, return it
      if ($item) {
        $json = json_encode(
          [
            'type' => $contentResolver->getType(),
            'data' => $item
          ]
        );

        $response->getBody()->write($json);
        return $response
          ->withStatus(201)
          ->withHeader('Content-Type', 'application/json');
      } else {
        $json = json_encode(['error' => 'Could not create item']);

        $response->getBody()->write($json);
        return $response
          ->withStatus(404)
          ->withHeader('Content-Type', 'application/json');
      }
    } catch (MissingFieldsException | InvalidFieldsException $e) {
      $json = json_encode(['error' => $e->getMessage()]);

      $response->getBody()->write($json);
      return $response
        ->withStatus(404)
        ->withHeader('Content-Type', 'application/json');
    }
  }
}
