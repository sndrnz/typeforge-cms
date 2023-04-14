<?php

namespace TypeForge\Exceptions;

use Exception;

class TypeNotFoundException extends Exception
{
  public function __construct(string $type)
  {
    parent::__construct("Type '{$type}' not found");
  }
}
