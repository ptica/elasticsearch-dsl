<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Query\Span;

use ONGR\ElasticsearchDSL\Query\Span\SpanQueryInterface;
use PHPUnit\Framework\TestCase;
use ONGR\ElasticsearchDSL\Query\Span\SpanContainingQuery;

/**
 * Unit test for SpanContainingQuery.
 */
class SpanContainingQueryTest extends TestCase
{
    /**
     * Tests for toArray().
     */
    public function testToArray(): void
    {
        $query = new SpanContainingQuery(
            $this->getSpanQueryMock('foo'),
            $this->getSpanQueryMock('bar')
        );
        $result = [
            'span_containing' => [
                'little' => [
                    'span_term' => ['user' => 'foo'],
                ],
                'big' => [
                    'span_term' => ['user' => 'bar'],
                ],
            ],
        ];
        $this->assertEquals($result, $query->toArray());
    }

    /**
     * @returns \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSpanQueryMock(string $value)
    {
        $mock = $this->createMock(SpanQueryInterface::class);
        $mock
            ->expects($this->once())
            ->method('toArray')
            ->willReturn(['span_term' => ['user' => $value]]);
        return $mock;
    }
}
