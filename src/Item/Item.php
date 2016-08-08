<?php

namespace Itk\EventDatabaseClient\Item;

abstract class Item {
  protected $data;

  public function __construct(array $data) {
    $this->data = $data;

    $this->data['id'] = $this->data['@id'];
  }

  public function get($key = null) {
    return $key ? (isset($this->data[$key]) ? $this->data[$key] : null) : $this->data;
  }

  public function __set($name, $value) {
    $this->data[$name] = $value;
  }

  public function __get($name) {
    return isset($this->data[$name]) ? $this->data[$name] : null;
  }

  public function __call($name, array $arguments) {
    if (preg_match('/^(?:get)?(?<name>.+)/', $name, $matches)) {
      $key = lcfirst($matches['name']);
      if (array_key_exists($key, $this->data)) {
        return $this->data[$key];
      }
    }

    throw new \Exception('Call to undefined method ' . get_class($this) . '::' . $name . '()');
  }
}
