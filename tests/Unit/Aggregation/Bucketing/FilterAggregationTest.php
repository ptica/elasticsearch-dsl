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

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\FilterAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\HistogramAggregation;
use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\ExistsQuery;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;

class FilterAggregationTest extends TestCase
{
    /**
     * Data provider for testToArray.
     */
    public static function getToArrayData(): array
    {
        $out = [];

        // Case #0 filter aggregation.
        $aggregation = new FilterAggregation('test_agg');
        $filter = new MatchAllQuery();

        $aggregation->setFilter($filter);

        $result = [
            'filter' => $filter->toArray(),
        ];

        $out[] = [
            $aggregation,
            $result,
        ];

        // Case #1 nested filter aggregation.
        $aggregation = new FilterAggregation('test_agg');
        $aggregation->setFilter($filter);

        $histogramAgg = new HistogramAggregation('acme', 'bar', 10);
        $aggregation->addAggregation($histogramAgg);

        $result = [
            'filter' => $filter->toArray(),
            'aggregations' => [
                $histogramAgg->getName() => $histogramAgg->toArray(),
            ],
        ];

        $out[] = [
            $aggregation,
            $result,
        ];

        // Case #2 testing bool filter.
        $aggregation = new FilterAggregation('test_agg');
        $matchAllFilter = new MatchAllQuery();
        $termFilter = new TermQuery('acme', 'foo');
        $boolFilter = new BoolQuery();
        $boolFilter->add($matchAllFilter);
        $boolFilter->add($termFilter);

        $aggregation->setFilter($boolFilter);

        $result = [
            'filter' => $boolFilter->toArray(),
        ];


        $out[] = [
            $aggregation,
            $result,
        ];

        return $out;
    }

    /**
     * Test for filter aggregation toArray() method.
     *
     * @param FilterAggregation $aggregation
     * @param array             $expectedResult
     */
    #[DataProvider('getToArrayData')]
    public function testToArray($aggregation, mixed $expectedResult): void
    {
        $this->assertEquals($expectedResult, $aggregation->toArray());
    }

    /**
     * Test for setField().
     */
    public function testSetField(): never
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("doesn't support `field` parameter");
        $aggregation = new FilterAggregation('test_agg');
        $aggregation->setField('test_field');
    }

    /**
     * Test for toArray() without setting a filter.
     */
    public function testToArrayNoFilter(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('has no filter added');
        $aggregation = new FilterAggregation('test_agg');
        $result = $aggregation->toArray();
        $this->assertEquals(
            [
                'aggregation' => [
                    'test_agg' => [
                        'filter' => []
                    ]
                ]
            ],
            $result
        );
    }

    /**
     * Test for toArray() with setting a filter.
     */
    public function testToArrayWithFilter(): void
    {
        $aggregation = new FilterAggregation('test_agg');
        $aggregation->setFilter(new ExistsQuery('test'));
        $result = $aggregation->toArray();
        $this->assertEquals(
            [
                'filter' => [
                    'exists' => [
                        'field' => 'test'
                    ]
                ]
            ],
            $result
        );
    }

    /**
     * Tests if filter can be passed to constructor.
     */
    public function testConstructorFilter(): void
    {
        $matchAllFilter = new MatchAllQuery();
        $aggregation = new FilterAggregation('test', $matchAllFilter);
        $this->assertEquals(
            [
                'filter' => $matchAllFilter->toArray(),
            ],
            $aggregation->toArray()
        );
    }
}
