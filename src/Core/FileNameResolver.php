<?php

namespace TypeForge\Core;

class FileNameResolver
{
  public static function schemaFile(string $type): string
  {
    return "../schema/{$type}.json";
  }

  public static function itemFile(string $type, string $id): string
  {
    $contentFolder = self::contentFolder($type);
    return "{$contentFolder}/{$id}.json";
  }

  public static function contentFolder(string $type): string
  {
    return "../content/{$type}";
  }
}
