<?php

namespace TypeForge\Helpers;

class LanguageHelper
{
  public static function pluralize(string $word): string
  {
    $last = $word[strlen($word) - 1];

    if ($last === 'y') {
      return substr($word, 0, -1) . 'ies';
    } else {
      return $word . 's';
    }
  }

  public static function singularize(string $word): string
  {
    $last = $word[strlen($word) - 1];

    if ($last === 's') {
      return substr($word, 0, -1);
    } else {
      return $word;
    }
  }
}
