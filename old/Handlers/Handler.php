<?php

namespace TypeForge\Handlers;

use TypeForge\Http\Request;
use TypeForge\Http\Response;

abstract class Handler
{
  public function __construct()
  {
  }

  abstract public function handle(Request $request): Response;
}
