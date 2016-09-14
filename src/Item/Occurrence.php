<?php

namespace Itk\EventDatabaseClient\Item;

class Occurrence extends Item {
  protected $place;

  public function __construct(array $data) {
    parent::__construct($data);

    if ($this->get('place')) {
      $this->place = new Place($this->get('place'));
    }
  }

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

  public function getPlace() {
    return $this->place;
  }

}
