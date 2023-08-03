<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Bucketing\Aggregation;

use ONGR\ElasticsearchDSL\Aggregation\AbstractAggregation;
use PHPUnit\Framework\TestCase;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\ChildrenAggregation;

/**
 * Unit test for children aggregation.
 */
class ChildrenAggregationTest extends TestCase
{
    /**
     * Tests if ChildrenAggregation#getArray throws exception when expected.
     */
    public function testGetArrayException(): void
    {
        $this->expectException(\LogicException::class);
        $aggregation = new ChildrenAggregation('foo');
        $aggregation->getArray();
    }

    /**
     * Tests getType method.
     */
    public function testChildrenAggregationGetType(): void
    {
        $aggregation = new ChildrenAggregation('foo');
        $result = $aggregation->getType();
        $this->assertEquals('children', $result);
    }

    /**
     * Tests getArray method.
     */
    public function testChildrenAggregationGetArray(): void
    {
        $mock = $this->getMockBuilder(AbstractAggregation::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $aggregation = new ChildrenAggregation('foo');
        $aggregation->addAggregation($mock);
        $aggregation->setChildren('question');

        $result = $aggregation->getArray();
        $expected = ['type' => 'question'];
        $this->assertEquals($expected, $result);
    }
}
