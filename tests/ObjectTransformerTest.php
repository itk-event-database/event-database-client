<?php

namespace Itk\EventDatabaseClient;

require_once __DIR__ . '/ObjectTransformerTest/ArrayValueHandler.php';

use Itk\EventDatabaseClient\ObjectTransformerTest\ArrayValueHandler;
use PHPUnit\Framework\TestCase;

class ObjectTransformerTest extends TestCase
{
    /**
     * @dataProvider testTransformProvider
     */
    public function testTransform(array $item, array $configuration, array $expected)
    {
        $valueHandler = new ArrayValueHandler();
        $transformer = new ObjectTransformer($valueHandler);

        $actual = $transformer->transformObject($item, $configuration);

        // if ($expected != $actual) {
        //     var_export(['actual' => $actual, 'expected' => $expected]);
        // }

        $this->assertEquals($expected, $actual);
    }

    public function testTransformProvider()
    {
        return [
            [
                [
                    'title' => 'Title',
                    'body' => 'Body',
                ],
                [
                    'mapping' => [
                        'name' => 'title',
                        'description' => 'body',
                    ],
                ],
                [
                    'name' => 'Title',
                    'description' => 'Body',
                ],
            ],

            [
                [
                    'title' => 'Title',
                ],
                [
                    'mapping' => [
                        'name' => 'title',
                    ],
                    'defaults' => [
                        'description' => 'Body',
                    ],
                ],
                [
                    'name' => 'Title',
                    'description' => 'Body',
                ],
            ],

            [
                // Item
                [
                    'title' => 'Title',
                    'body' => 'Yada, yada, yada, …',
                    'field_starttime' => new \DateTime('2001-01-01'),
                    'field_endtime' => new \DateTime('2001-12-31'),
                ],
                // Configuration
                [
                    'mapping' => [
                        'name' => 'title',
                        'description' => 'body',
                        'occurrences' => [
                            'mapping' => [
                                'startDate' => 'field_starttime',
                                'endDate' => 'field_endtime',
                                'place' => [
                                    'type' => 'object',
                                    'mapping' => [
                                    ],
                                    'defaults' => [
                                        'name' => 'Dokk1',
                                    ],
                                ],
                            ],
                        ]
                    ],
                ],
                // Expected
                [
                    'name' => 'Title',
                    'description' => 'Yada, yada, yada, …',
                    'occurrences' => [
                        [
                            'startDate' => new \DateTime('2001-01-01'),
                            'endDate' => new \DateTime('2001-12-31'),
                            'place' => [
                                'name' => 'Dokk1',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
