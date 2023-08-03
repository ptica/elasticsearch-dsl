<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Aggregation\Pipeline;

use ONGR\ElasticsearchDSL\Sort\FieldSort;

/**
 * Class representing Bucket Script Pipeline Aggregation.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-bucket-sort-aggregation.html
 */
class BucketSortAggregation extends AbstractPipelineAggregation
{
    private array $sort = [];

    /**
     * @param string $name
     * @param string $bucketsPath
     */
    public function __construct(string $name, $bucketsPath = null)
    {
        parent::__construct($name, $bucketsPath);
    }

    /**
     * @return self
     */
    public function addSort(FieldSort $sort): void
    {
        $this->sort[] = $sort->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'bucket_sort';
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array|\stdClass
    {
        return array_filter(
            [
                'buckets_path' => $this->getBucketsPath(),
                'sort' => $this->getSort(),
            ]
        );
    }

    public function getSort(): array
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     *
     * @return $this
     */
    public function setSort(array $sort)
    {
        $this->sort = $sort;

        return $this;
    }
}
