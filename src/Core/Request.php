<?php

namespace TypeForge\Core;

class Request
{
  private string $method;
  private string $route;
  private array $query;
  private array|null $body;

  public function __construct(bool $json)
  {
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->route = explode('?', $_SERVER['REQUEST_URI'])[0];
    $this->query = $_GET;
    $this->body = $json ? json_decode(file_get_contents('php://input'), true) : $_POST;
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
