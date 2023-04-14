<?php

namespace TypeForge\Exceptions;

use Exception;

class InvalidFieldsException extends Exception
{
  public function __construct(string $type, array $fields)
  {
    $fields = implode(', ', $fields);
    parent::__construct("Invalid fields for type '{$type}': {$fields}");
  }
}
