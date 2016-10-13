<?php

namespace Itk\EventDatabaseClient\Item;

class Event extends Item
{
    protected $occurrences;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->occurrences = [];
        if ($this->get('occurrences')) {
            $this->occurrences = array_map(function ($item) {
                return new Occurrence($item);
            }, $this->get('occurrences'));
        }
    }

    public function __toString()
    {
        return $this->getName() ?: __CLASS__;
    }

    public function getName()
    {
        return $this->get('name');
    }

    public function getUrl()
    {
        return $this->get('url');
    }

    public function getDescription()
    {
        return $this->get('description');
    }

    public function getOccurrences()
    {
        return $this->occurrences;
    }
}
