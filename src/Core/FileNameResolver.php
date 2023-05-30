<?php

namespace TypeForge\Core;

class FileNameResolver
{
  private static function getRoot(): string
  {
    return $_SERVER['DOCUMENT_ROOT'];
  }

  public static function schemaFile(string $type): string
  {
    $root = self::getRoot();
    return "{$root}/schema/{$type}.toml";
  }

  public static function itemFile(string $type, string $id): string
  {
    $contentFolder = self::contentFolder($type);
    return "{$contentFolder}/{$id}.toml";
  }

  public static function contentFolder(string $type): string
  {
    $root = self::getRoot();
    return "{$root}/content/{$type}";
  }
}
