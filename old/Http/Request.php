<?php

namespace TypeForge\Http;

use TypeForge\Http\BodyParser\BodyParser;

class Request
{
  private string $method;
  private string $route;
  private array $query;
  private array|null $body;

  public function __construct(BodyParser $bodyParser)
  {
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->route = explode('?', $_SERVER['REQUEST_URI'])[0];
    $this->query = $_GET;
    $this->body = $bodyParser->parse();
  }

  public function getMethod()
  {
    return $this->method;
  }

  public function getRoute()
  {
    return $this->route;
  }

  public function getQuery()
  {
    return $this->query;
  }

  public function getBody()
  {
    return $this->body;
  }
}
