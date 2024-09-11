<?php

namespace Itk\EventDatabaseClient\v2\Item;

class Occurrence extends Item
{
    protected $place;
    protected $event;

    public function __construct(array $data)
    {
        parent::__construct($data);

        if ($this->get('place')) {
            $this->place = new Place($this->get('place'));
        }
        if (is_array($this->get('event'))) {
            $this->event = new Event($this->get('event'));
        }
    }

    public function __toString()
    {
        $s = $this->getStart();
        $s .= '–';
        $s .= $this->getEnd() ?: '';
        return $s;
    }

    public function getTicketPriceRange()
    {
        return $this->get('ticketPriceRange');
    }

    public function getStart()
    {
        return $this->get('start');
    }

    public function getEnd()
    {
        return $this->get('end');
    }

    public function getRoom()
    {
      return $this->get('room');
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function getEvent()
    {
        return $this->event;
    }
}
