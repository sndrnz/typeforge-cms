<?php

namespace TypeForge\Core;

class Config
{
  private array $config = array();

  /**
   * set a config value
   * @param string $key
   * @param mixed $value
   */
  public function set(string $key, $value)
  {
    $this->config[$key] = $value;
  }

  /**
   * get a config value
   * @param string $key
   */
  public function get(string $key): mixed
  {
    return isset($this->config[$key]) ? $this->config[$key] : null;
  }
}
