<?php

namespace TypeForge\Http\BodyParser;

class JsonBodyParser implements BodyParser
{
  public function parse(): array
  {
    return $_POST;
  }
}
