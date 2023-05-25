<?php

namespace TypeForge\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use TypeForge\Core\ContentResolver;
use TypeForge\Exceptions\FailedToCreateItemException;
use TypeForge\Exceptions\InvalidDataException;
use TypeForge\Exceptions\NoTypeException;

class ContentController extends BaseController
{
  public function getContent(Request $request, Response $response)
  {
    $query = $request->getQueryParams();
    $type = isset($query['type']) ? $query['type'] : null;
    $id = isset($query['id']) ? $query['id'] : null;

    // if no type is given, return 404 error
    if (!$type) {
      throw new NoTypeException();
    }

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
  }

  public function createContent(Request $request, Response $response)
  {
    $data = $request->getParsedBody();

    $type = isset($data['type']) ? $data['type'] : null;
    $data = isset($data['data']) ? $data['data'] : null;

    // if no type is given, return 404 error
    if (!$type) {
      throw new NoTypeException();
    }

    // if data is empty return 422 error
    if (!$data) {
      throw new InvalidDataException();
    }

    // create item
    $contentResolver = new ContentResolver($type);
    $item = $contentResolver->create($data);

    if (!$item) {
      throw new FailedToCreateItemException();
    }

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
  }
}
