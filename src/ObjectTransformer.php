<?php

namespace Itk\EventDatabaseClient;

use Itk\EventDatabaseClient\ObjectTransformer\ValueHandlerInterface;

class ObjectTransformer
{
    protected $valueHandler;

    public function __construct(ValueHandlerInterface $valueHandler)
    {
        $this->valueHandler = $valueHandler;
    }

    public function transformObject($item, $config)
    {
        $data = $this->getData($item, $config);

        return $data;
    }

    protected function getData($item, array $config)
    {
        $data = [];

        if (isset($config['mapping'])) {
            $mapping = $config['mapping'];

            foreach ($mapping as $key => $spec) {
                if (!is_array($spec)) {
                    $path = $spec;
                    $value = $this->getItemValue($item, $path);
                    if ($value !== null) {
                        $data[$key] = $this->convertValue($value, $key);
                    }
                } elseif (isset($spec['mapping'])) {
                    $path = isset($spec['path']) ? $spec['path'] : '.';
                    $items = ($path === '.') ? [$item] : $this->getItemValue($item, $path);
                    if ($items) {
                        $data[$key] = array_map(function ($item) use ($spec) {
                            return $this->getData($item, $spec);
                        }, $items);
                    }
                } elseif (isset($spec['path'])) {
                    $path = $spec['path'];
                    $value = $this->getItemValue($item, $path);
                    if ($value !== null) {
                        if (isset($spec['split'])) {
                            $pattern = '/\s*' . preg_quote($spec['split'], '/') . '\s*/';
                            $data[$key] = preg_split($pattern, $value, null, PREG_SPLIT_NO_EMPTY);
                        } else {
                            $data[$key] = $this->convertValue($value, $key);
                        }
                    }
                }
            }

            if (isset($config['defaults'])) {
                $this->setDefaults($data, $config['defaults']);
            }
        }

        return $data;
    }

    protected function getItemValue($item, $path)
    {
        return $this->valueHandler ? $this->valueHandler->getValue($item, $path) : null;
    }

    protected function convertValue($value, $key)
    {
        return $this->valueHandler ? $this->valueHandler->convertValue($value, $key) : $value;
    }

    protected function setDefaults(array &$data, array $defaults)
    {
        foreach ($defaults as $key => $spec) {
            switch ($key) {
                case 'occurrences':
                    if (isset($data['occurrences'])) {
                        foreach ($data['occurrences'] as &$occurrence) {
                            $this->setDefaults($occurrence, $spec);
                        }
                    }
                    break;
                default:
                    $this->setDefaultValue($data, $key, $spec);
                    break;
            }
        }
    }

    protected function setDefaultValue(array &$data, $key, $spec)
    {
        if (empty($data[$key])) {
            $value = isset($spec['value']) ? $spec['value'] : $spec;
            $data[$key] = $this->convertValue($value, $key);
        } elseif (isset($spec['append']) && $spec['append'] == 'true') {
            if (is_array($data[$key])) {
                if (is_array($spec['value'])) {
                    foreach ($spec['value'] as $value) {
                        $data[$key][] = $this->convertValue($value, $key);
                    }
                } else {
                    $value = $spec['value'];
                    $data[$key][] = $this->convertValue($value, $key);
                }
            }
        }
    }
}
