<?php

namespace ONGR\ElasticsearchDSL\Aggregation\Pipeline;

use ONGR\ElasticsearchDSL\Aggregation\AbstractAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Type\MetricTrait;

abstract class AbstractPipelineAggregation extends AbstractAggregation
{
    use MetricTrait;

    /**
     * @var string
     */
    private $bucketsPath;

    /**
     * @param string $name
     * @param $bucketsPath
     */
    public function __construct(string $name, $bucketsPath = null)
    {
        parent::__construct($name);
        $this->setBucketsPath($bucketsPath);
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array|\stdClass
    {
        return ['buckets_path' => $this->getBucketsPath()];
    }

    /**
     * @return string
     */
    public function getBucketsPath()
    {
        return $this->bucketsPath;
    }

    /**
     * @param string $bucketsPath
     *
     * @return $this
     */
    public function setBucketsPath($bucketsPath)
    {
        $this->bucketsPath = $bucketsPath;

        return $this;
    }
}
