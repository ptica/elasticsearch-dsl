<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Aggregation\Pipeline;

use PHPUnit\Framework\TestCase;
use ONGR\ElasticsearchDSL\Aggregation\Pipeline\StatsBucketAggregation;

/**
 * Unit test for stats bucket aggregation.
 */
class StatsBucketAggregationTest extends TestCase
{
    /**
     * Tests toArray method.
     */
    public function testToArray(): void
    {
        $aggregation = new StatsBucketAggregation('acme', 'test');

        $expected = [
            'stats_bucket' => [
                'buckets_path' => 'test',
            ],
        ];

        $this->assertEquals($expected, $aggregation->toArray());
    }
}
