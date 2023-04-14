<?php

namespace TypeForge\Http;

enum Method
{
  case GET;
  case POST;
  case PUT;
  case DELETE;
  case PATCH;
  case HEAD;
  case OPTIONS;
  case TRACE;
}

class Route
{
  private string $path;
  private Method $method;
  private array $handlers;

  public function __construct(Method $method, string $path, array $handlers)
  {
  }

  public function execute(Request $request): void
  {
    foreach ($this->handlers as $handler) {
      $handler->handle($request);
    }
  }
}
