<?php

namespace TypeForge\Http\BodyParser;

class JsonBodyParser implements BodyParser
{
  public function parse(): array
  {
    return json_decode(file_get_contents('php://input'), true);
  }
}
