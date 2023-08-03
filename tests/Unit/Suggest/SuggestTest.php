<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Suggest;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use ONGR\ElasticsearchDSL\Suggest\Suggest;

class SuggestTest extends TestCase
{
    /**
     * Tests getType method.
     */
    public function testSuggestGetType(): void
    {
        $suggest = new Suggest('foo', 'term', 'acme', 'bar');
        $this->assertEquals('term', $suggest->getType());
    }

    /**
     * Data provider for testToArray()
     *
     * @return array[]
     */
    public static function getTestToArrayData(): array
    {
        return [
            [
                'suggest' => new Suggest(
                    'foo',
                    'term',
                    'bar',
                    'acme',
                    ['size' => 5]
                ),
                'expected' => [
                    'foo' => [
                        'text' => 'bar',
                        'term' => [
                            'field' => 'acme',
                            'size' => 5
                        ]
                    ]
                ]
            ],
            [
                'suggest' => new Suggest(
                    'foo',
                    'phrase',
                    'bar',
                    'acme',
                    ['max_errors' => 0.5]
                ),
                'expected' => [
                    'foo' => [
                        'text' => 'bar',
                        'phrase' => [
                            'field' => 'acme',
                            'max_errors' => 0.5,
                        ],
                    ]
                ]
            ],
            [
                'suggest' => new Suggest(
                    'foo',
                    'completion',
                    'bar',
                    'acme',
                    ['fuzziness' => 2]
                ),
                'expected' => [
                    'foo' => [
                        'text' => 'bar',
                        'completion' => [
                            'field' => 'acme',
                            'fuzziness' => 2
                        ]
                    ]
                ]
            ],
            [
                'suggest' => new Suggest(
                    'foo',
                    'completion',
                    'bar',
                    'acme',
                    ['context' => ['color' => 'red'], 'size' => 3]
                ),
                'expected' => [
                    'foo' => [
                        'text' => 'bar',
                        'completion' => [
                            'field' => 'acme',
                            'size' => 3,
                            'context' => [
                                'color' => 'red'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    #[DataProvider('getTestToArrayData')]
    public function testToArray(Suggest $suggest, array $expected): void
    {
        $this->assertEquals($expected, $suggest->toArray());
    }
}
