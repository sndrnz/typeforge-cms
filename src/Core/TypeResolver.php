<?php

namespace TypeForge\Core;

use TypeForge\Exceptions\TypeNotFoundException;

class TypeResolver
{
  private string $type;
  private array|null $typeData = null;

  public function __construct(string $type)
  {
    $this->type = $type;
  }

  public function getType(): array|null
  {
    // check if the type data is already loaded
    if ($this->typeData) {
      return $this->typeData;
    }

    // get the schema file name
    $schemaFile = FileNameResolver::schemaFile($this->type);

    // check if the file exists
    if (!file_exists($schemaFile)) {
      throw new TypeNotFoundException($this->type);
      return null;
    }

    // get the file content and decode it
    $content = file_get_contents($schemaFile);
    if (!$content) {
      return null;
    }
    $type = json_decode($content, true);

    // cache the type data
    $this->typeData = $type;
    return $type;
  }

  /**
   * get the content folder for a type
   * @return string|null
   */
  public function getContentFolder(): string|null
  {
    $type = $this->getType();

    if (!$type) {
      return null;
    }

    $plural_name = $type['plural'];

    return FileNameResolver::contentFolder($plural_name);
  }

  /**
   * get the item file for a type and id
   * @param string $id
   * @return string|null
   */
  public function getItemFile(string $id): string|null
  {
    $type = $this->getType();

    if (!$type) {
      return null;
    }

    $plural_name = $type['plural'];

    return FileNameResolver::itemFile($plural_name, $id);
  }
}
