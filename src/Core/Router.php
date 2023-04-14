<?php

namespace TypeForge\Core;

class Router
{
  private array $routes = array();
  private Config $config;

  public function __construct(Config $config)
  {
    $this->config = $config;
  }

  /**
   * register a GET route
   * @param string $route
   * @param callable $callback
   */
  public function get(string $route, callable ...$callbacks)
  {
    $key = $this->methodRouteKey("GET", $route);
    $this->routes[$key] = [...$callbacks];
  }

  /**
   * register a POST route
   * @param string $route
   * @param callable $callback
   */
  public function post(string $route, callable ...$callbacks)
  {
    $key = $this->methodRouteKey("POST", $route);
    $this->routes[$key] = [...$callbacks];
  }

  /**
   * run the router
   */
  public function run()
  {
    // get the current route and method
    $request = new Request($this->config->get("json") ?? false);

    // remove the query string from the route

    $methodRoute = $this->methodRouteKey($request->getMethod(), $request->getRoute());

    // check if the route exists
    if (array_key_exists($methodRoute, $this->routes)) {
      $callbacks = $this->routes[$methodRoute];
      foreach ($callbacks as $callback) {
        $callback($request);
      }
    } else {
      echo "Not found";
      http_response_code(404);
    }
  }

  /**
   * create a key for the routes array
   * @param string $method
   * @param string $route
   * @return string
   */
  private function methodRouteKey(string $method, string $route)
  {
    return $method . ":" . $route;
  }
}
