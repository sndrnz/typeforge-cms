<?php

namespace TypeForge\Exceptions;

use Exception;

class NoTypeException extends Exception
{
  public function __construct()
  {
    $this->code = 400;
    $this->message = "No type given";
  }
}
