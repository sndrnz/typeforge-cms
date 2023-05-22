<?php

namespace TypeForge\Http\BodyParser;

interface BodyParser
{
  public function parse(): array;
}
