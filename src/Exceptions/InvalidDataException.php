<?php

namespace TypeForge\Exceptions;

use Exception;

class InvalidDataException extends Exception
{
  public function __construct()
  {
    $this->code = 422;
    $this->message = "Invalid data";
  }
}
