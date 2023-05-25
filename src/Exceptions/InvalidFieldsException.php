<?php

namespace TypeForge\Exceptions;

use Exception;

class InvalidFieldsException extends Exception
{
  public function __construct(string $type, array $fields)
  {
    $fields = implode(', ', $fields);
    $this->code = 422;
    $this->message = "Invalid fields for type '{$type}': {$fields}";
  }
}
