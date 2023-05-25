<?php

namespace TypeForge\Exceptions;

use Exception;

class TypeNotFoundException extends Exception
{
  public function __construct(string $type)
  {
    $this->code = 404;
    $this->message = "Type '{$type}' not found";
  }
}
