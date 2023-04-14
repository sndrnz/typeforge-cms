<?php

namespace TypeForge;

class ContentType
{
  public $name;
  public $fields;

  public function __construct($name, $fields)
  {
    $this->name = $name;
    $this->fields = $fields;
  }

  public function getItems()
  {
    $items = array();
    $files = glob('data/' . $this->name . '/*.json');

    foreach ($files as $file) {
      $items[] = json_decode(file_get_contents($file), true);
    }

    return $items;
  }

  public function getItem($id)
  {
    $file = 'data/' . $this->name . '/' . $id . '.json';

    if (file_exists($file)) {
      return json_decode(file_get_contents($file), true);
    } else {
      return null;
    }
  }
}

// get all json files in the schema directory
$files = glob('schema/*.json');

// map all files and their name without .json as the key and the decoded json data as value to a new array called schema
$schema = array_map(function ($file) {
  return json_decode(file_get_contents($file), true);
}, array_combine(array_map(function ($file) {
  return str_replace('.json', '', basename($file));
}, $files), $files));

// print schema array with pre
echo '<pre>';
print_r($schema);
echo '</pre>';
