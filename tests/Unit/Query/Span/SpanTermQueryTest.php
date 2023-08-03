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

use PHPUnit\Framework\TestCase;
use ONGR\ElasticsearchDSL\Query\Span\SpanTermQuery;

/**
 * Unit test for SpanTermQuery.
 */
class SpanTermQueryTest extends TestCase
{
    /**
     * Tests for toArray().
     */
    public function testToArray(): void
    {
        $query = new SpanTermQuery('user', 'bob');
        $expected = [
            'span_term' => ['user' => 'bob'],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests for toArray() with parameters.
     */
    public function testToArrayWithParameters(): void
    {
        $query = new SpanTermQuery('user', 'bob', ['boost' => 2]);
        $expected = [
            'span_term' => ['user' => ['value' => 'bob', 'boost' => 2]],
        ];

        $this->assertEquals($expected, $query->toArray());
    }
}
