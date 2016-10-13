<?php

namespace Itk\EventDatabaseClient\ObjectTransformerTest;

use Itk\EventDatabaseClient\ObjectTransformer\ValueHandler;

class ArrayValueHandler extends ValueHandler
{
    public function getValue($item, $path)
    {
        if (!is_array($item)) {
            throw new \Exception('array expected');
        }

        $data = $item;
        $steps = preg_split('/\s*\.\s*/', $path, null, PREG_SPLIT_NO_EMPTY);
        foreach ($steps as $step) {
            if (array_key_exists($step, $data)) {
                $data = $data[$step];
            } else {
                throw new \Exception('Invalid path: ' . $path);
            }
        }

        return $data;
    }

    protected function convertDate($value)
    {
        return $value;
    }

    public function makeUrlAbsolute($value)
    {
        return $value;
    }
}
