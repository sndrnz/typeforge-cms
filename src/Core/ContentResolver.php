<?php

namespace TypeForge\Core;

use TypeForge\Exceptions\InvalidFieldsException;
use TypeForge\Exceptions\MissingFieldsException;
use Yosymfony\Toml\Toml;
use Yosymfony\Toml\TomlBuilder;

class ContentResolver
{
  private string $type;
  private SchemaResolver $schemaResolver;

  /**
   * @param string $type singular type name
   */
  public function __construct(string $type)
  {
    $this->type = $type;
    $this->schemaResolver = new SchemaResolver($type);
  }

  /**
   * get all items of a type
   * @return array
   */
  public function getAll(): array
  {
    $item_folder = $this->schemaResolver->getContentFolder();

    // if the folder does not exist, return an empty array
    if (!file_exists($item_folder)) {
      return [];
    }

    $items = array();

    // get all files in the folder
    $files = glob("{$item_folder}/*.toml");
    foreach ($files as $file) {
      // get the file content and decode it
      // $item = json_decode(file_get_contents($file), true);
      $content = file_get_contents($file);
      $item = Toml::parse($content);
      $items[] = $item;
    }

    return $items;
  }

  /**
   * get a single item by id
   * @param string $id
   * @return array|null
   */
  public function getById(string $id): array|null
  {
    $item_file = $this->schemaResolver->getItemFile($id);

    // if the file does not exist, return null
    if (!file_exists($item_file)) {
      return null;
    }

    // get the file content and decode it
    // $item = json_decode(file_get_contents($item_file), true);

    $content = file_get_contents($item_file);
    $item = Toml::parse($content);

    return $item;
  }

  /**
   * get the type name
   * @return string
   */
  public function getType(): string
  {
    return $this->type;
  }

  /**
   * create a new item
   * @param array $data
   * @return bool
   */
  public function create(array $data): array|false
  {
    $item_folder = $this->schemaResolver->getContentFolder();

    // if the folder does not exist, return an empty array
    if (!file_exists($item_folder)) {
      // create the folder
      mkdir($item_folder, 0777, true);
    }

    // check if $data matches the fields defined in the fields of the type
    $type = $this->schemaResolver->getType();
    $fields = $type['fields'];
    $missingFields = array();
    foreach ($fields as $field) {
      $name = $field['name'];
      if (!isset($data[$name])) {
        $missingFields[] = $name;
      }
    }

    if (count($missingFields) > 0) {
      throw new MissingFieldsException($this->type, $missingFields);
    }

    // check if $data contains invalid fields
    $invalidFields = array_diff(array_keys($data), array_column($fields, 'name'));

    if (count($invalidFields) > 0) {
      throw new InvalidFieldsException($this->type, $invalidFields);
    }

    $id = uniqid();

    $item = array_merge(
      [
        'id' => $id
      ],
      $data
    );

    $item_file = $this->schemaResolver->getItemFile($id);

    // $item_json = json_encode($item, JSON_PRETTY_PRINT);

    $toml_builder = new TomlBuilder();

    foreach ($item as $key => $value) {
      $toml_builder->addValue($key, $value);
    }

    $item_toml = $toml_builder->getTomlString();

    return file_put_contents($item_file, $item_toml) !== false ? $item : false;
  }
}
