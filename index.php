<?php

require __DIR__ . '/vendor/autoload.php';

use TypeForge\Core\ContentResolver;
use TypeForge\Core\Router;
use TypeForge\Core\Config;
use TypeForge\Http\Request;
use TypeForge\Exceptions\InvalidFieldsException;
use TypeForge\Exceptions\TypeNotFoundException;
use TypeForge\Exceptions\MissingFieldsException;

$config = new Config();
$config->set("json", true);
$router = new Router($config);

$router->get('/', function () {
  echo 'Home';
});

$router->get('/about', function () {
  echo 'About';
});

$router->post('/contact', function () {
  echo 'Contact';
});

$router->get('/api/content', function (Request $request) {
  header('Content-Type: application/json');
  $type = isset($_GET['type']) ? $_GET['type'] : null;
  $id = isset($_GET['id']) ? $_GET['id'] : null;

  // if no type is given, return 404 error
  if (!$type) {
    echo json_encode(['error' => 'Type not found']);
    http_response_code(404);
    return;
  }

  try {
    $contentResolver = new ContentResolver($type);

    // if id is given, return single item, otherwise return all items
    $data = $id ? $contentResolver->getById($id) : $contentResolver->getAll();

    echo json_encode(
      [
        'type' => $contentResolver->getType(),
        'data' => $data
      ]
    );
  } catch (TypeNotFoundException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(404);
    return;
  }
});

$router->post('/api/content', function () {
  header('Content-Type: application/json');
  $data = json_decode(file_get_contents('php://input'), true);
  $type = isset($data['type']) ? $data['type'] : null;
  $data = isset($data['data']) ? $data['data'] : null;

  // if no type is given, return 404 error
  if (!$type) {
    echo json_encode(['error' => 'Type not found']);
    http_response_code(404);
    return;
  }


  // if data is empty return 422 error
  if (!$data) {
    echo json_encode(['error' => 'Invalid data']);
    http_response_code(422);
    return;
  }

  try {
    // create item
    $contentResolver = new ContentResolver($type);
    $item = $contentResolver->create($data);

    // if item was created, return it
    if ($item) {
      echo json_encode(
        [
          'type' => $contentResolver->getType(),
          'data' => $item
        ]
      );
    } else {
      echo json_encode(['error' => 'Could not create item']);
      http_response_code(500);
      return;
    }
  } catch (MissingFieldsException | InvalidFieldsException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(404);
    return;
  }
});

// $router->get("/api/schema", function () {
//   $type = $_GET['type'];

//   // if no type is given, return 404 error
//   if (!$type) {
//     echo json_encode(['error' => 'Type not found']);
//     http_response_code(404);
//     return;
//   }

//   try {
//     $contentResolver = new ContentResolver($type);
//     $schema = $contentResolver->getSchema();

//     header('Content-Type: application/json');
//     echo json_encode(
//       [
//         'type' => $contentResolver->getType(),
//         'schema' => $schema
//       ]
//     );
//   } catch (TypeNotFoundException $e) {
//     echo json_encode(['error' => $e->getMessage()]);
//     http_response_code(404);
//     return;
//   }
// });

$router->run();
