<?php

namespace Itk\EventDatabaseClient\ObjectTransformer;

abstract class ValueHandler implements ValueHandlerInterface {
  abstract public function getValue($item, $path);
  abstract protected function makeUrlAbsolute($value);

  public function convertValue($value, $key) {
    return $this->convert($value, $key);
  }

  protected function convert($value, $name) {
    switch ($name) {
      case 'startDate':
      case 'endDate':
        return $this->convertDate($value);

      case 'image':
      case 'url':
        return $this->makeUrlAbsolute($value);

    }

    return $value;
  }

  /**
   * Converts a value into a date formatted as a string (ISO8601).
   *
   * @param $value
   * @return \DateTime|null|string
   */
  protected function convertDate($value) {
    if ($value instanceof \DateTime) {
      $data = $value;
    }
    else {
      $date = NULL;
      // JSON date (/Date(...)/)
      if (preg_match('@/Date\(([0-9]+)\)/@', $value, $matches)) {
        $date = new \DateTime();
        $date->setTimestamp(((int) $matches[1]) / 1000);
      }
      else {
        if (is_numeric($value)) {
          $date = new \DateTime();
          $date->setTimestamp($value);
        }
      }

      if ($date === NULL) {
        try {
          $date = new \DateTime($value);
        } catch (\Exception $e) {
        }
      }
    }

    return $date ? $date->format(\DateTime::ISO8601) : null;
  }

}
