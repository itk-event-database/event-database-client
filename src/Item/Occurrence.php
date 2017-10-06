<?php

namespace Itk\EventDatabaseClient\Item;

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
        $s = $this->getStartDate();
        $s .= '–';
        $s .= $this->getEndDate() ?: '';
        return $s;
    }

    public function getTicketPriceRange()
    {
        return $this->get('ticketPriceRange');
    }

    public function getStartDate()
    {
        return $this->get('startDate');
    }

    public function getEndDate()
    {
        return $this->get('endDate');
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
