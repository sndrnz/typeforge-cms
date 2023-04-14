<?php

namespace TypeForge\Exceptions;

use Exception;

class MissingFieldsException extends Exception
{
  public function __construct(string $type, array $fields)
  {
    $fields = implode(', ', $fields);
    parent::__construct("Missing fields for type '{$type}': {$fields}");
  }
}
