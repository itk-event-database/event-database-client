<?php

namespace Itk\EventDatabaseClient\Item;

class Place extends Item
{
    public function __toString()
    {
        return $this->getName() ?: '';
    }

    public function getName()
    {
        return $this->get('name');
    }
}
