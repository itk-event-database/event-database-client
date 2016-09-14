<?php

namespace Itk\EventDatabaseClient\ObjectTransformer;

interface ValueHandlerInterface {
  public function getValue($item, $path);

  public function convertValue($value, $key);
}
