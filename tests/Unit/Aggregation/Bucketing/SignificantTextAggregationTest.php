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
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\SignificantTextAggregation;

/**
 * Unit test for children aggregation.
 */
class SignificantTextAggregationTest extends TestCase
{
    /**
     * Tests getType method.
     */
    public function testSignificantTextAggregationGetType(): void
    {
        $aggregation = new SignificantTextAggregation('foo');
        $result = $aggregation->getType();
        $this->assertEquals('significant_text', $result);
    }

    /**
     * Tests getArray method.
     */
    public function testSignificantTermsAggregationGetArray(): void
    {
        $mock = $this->getMockBuilder(AbstractAggregation::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $aggregation = new SignificantTextAggregation('foo', 'title');
        $aggregation->addAggregation($mock);

        $result = $aggregation->getArray();
        $expected = ['field' => 'title'];
        $this->assertEquals($expected, $result);
    }
}
