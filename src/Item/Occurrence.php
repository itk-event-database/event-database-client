<?php

namespace Itk\EventDatabaseClient\Item;

class Occurrence extends Item {
  public function __toString() {
    $s = $this->getStartDate();
    $s .= '–';
    $s .= $this->getEndDate() ?: '';
    return $s;
  }

  public function getStartDate() {
    return $this->get('startDate');
  }

  public function getEndDate() {
    return $this->get('endDate');
  }

  public function getVenue() {
    return $this->get('venue');
  }

}
