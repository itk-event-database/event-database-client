<?php

namespace Itk\EventDatabaseClient\v2\ObjectTransformer;

interface ValueHandlerInterface
{
    public function getValue($item, $path);

    public function convertValue($value, $key);
}
