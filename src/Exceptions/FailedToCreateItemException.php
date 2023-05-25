<?php

namespace TypeForge\Exceptions;

use Exception;

class FailedToCreateItemException extends Exception
{
  public function __construct()
  {
    $this->code = 500;
    $this->message = "Failed to create item";
  }
}
