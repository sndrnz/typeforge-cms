<?php

namespace TypeForge\Http;

class RouteCollection implements \IteratorAggregate
{
  private $routes = [];

  public function addRoute(Route $route): void
  {
    $this->routes[] = $route;
  }

  public function getIterator(): \ArrayIterator
  {
    return new \ArrayIterator($this->routes);
  }
}
