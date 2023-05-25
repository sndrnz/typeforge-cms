<?php

namespace TypeForge\Controller;

use Psr\Container\ContainerInterface;

class BaseController
{
  public $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function __get($property)
  {
    if ($this->container->has($property)) {
      return $this->container->get($property);
    }
  }
}
