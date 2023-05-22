<?php

namespace TypeForge\Http;

class Response
{
  private int $status;
  private array $headers;
  private string $body;

  public function __construct(int $status)
  {
    $this->status = $status;
  }

  public function json(array $body): Response
  {
    $this->body = json_encode($body);
    $this->headers['Content-Type'] = 'application/json';
    return $this;
  }

  public function send(): void
  {
    http_response_code($this->status);
    foreach ($this->headers as $key => $value) {
      header("$key: $value");
    }
    echo $this->body;
  }
}
