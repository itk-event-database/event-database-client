<?php

namespace Itk\EventDatabaseClient;

class Collection
{
    protected $data;
    protected $items;

    public function __construct(array $data, $memberClassName = null)
    {
        $this->data = $data;

        $this->items = [];
        if ($memberClassName) {
            foreach ($this->data['hydra:member'] as $item) {
                $this->items[] = new $memberClassName($item);
            }
        } else {
            $this->items = $this->data['hydra:member'];
        }
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getCount()
    {
        return count($this->items);
    }

    public function get($key)
    {
        switch ($key) {
            case 'first':
            case 'next':
            case 'previous':
            case 'last':
                $hydraKey = 'hydra:' . $key;
                return isset($this->data['hydra:view'][$hydraKey]) ? $this->data['hydra:view'][$hydraKey] : null;
            case 'totalItems':
                $hydraKey = 'hydra:' . $key;
                return isset($this->data[$hydraKey]) ? $this->data[$hydraKey] : null;
        }

        throw new \Exception('No such value: ' . $key);
    }

    public function __call($name, array $arguments)
    {
        if (preg_match('/^get(?<name>.+)/', $name, $matches)) {
            $key = lcfirst($matches['name']);
            try {
                return $this->get($key);
            } catch (\Exception $e) {
            }
        }

        throw new \Exception('Call to undefined method ' . get_class($this) . '::' . $name . '()');
    }
}
