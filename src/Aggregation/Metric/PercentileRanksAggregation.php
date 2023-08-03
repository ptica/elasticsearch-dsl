<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Aggregation\Metric;

use ONGR\ElasticsearchDSL\Aggregation\AbstractAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Type\MetricTrait;
use ONGR\ElasticsearchDSL\ScriptAwareTrait;

/**
 * Class representing Percentile Ranks Aggregation.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-percentile-rank-aggregation.html
 */
class PercentileRanksAggregation extends AbstractAggregation
{
    use MetricTrait;
    use ScriptAwareTrait;

    /**
     * @var array
     */
    private $values;

    /**
     * @var int
     */
    private $compression;

    /**
     * Inner aggregations container init.
     *
     * @param string $name
     * @param string $field
     * @param array  $values
     * @param string $script
     * @param int    $compression
     */
    public function __construct(string $name, $field = null, $values = null, $script = null, $compression = null)
    {
        parent::__construct($name);

        $this->setField($field);
        $this->setValues($values);
        $this->setScript($script);
        $this->setCompression($compression);
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function setValues($values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return int
     */
    public function getCompression()
    {
        return $this->compression;
    }

    /**
     * @param int $compression
     *
     * @return $this
     */
    public function setCompression($compression)
    {
        $this->compression = $compression;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'percentile_ranks';
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array
    {
        $out = array_filter(
            [
                'field' => $this->getField(),
                'script' => $this->getScript(),
                'values' => $this->getValues(),
                'compression' => $this->getCompression(),
            ],
            static fn($val): bool => $val || is_numeric($val)
        );

        $this->isRequiredParametersSet($out);

        return $out;
    }

    /**
     *
     * @return bool
     * @throws \LogicException
     */
    private function isRequiredParametersSet(array $a)
    {
        if (array_key_exists('field', $a) && array_key_exists('values', $a)
            || (array_key_exists('script', $a) && array_key_exists('values', $a))
        ) {
            return true;
        }

        throw new \LogicException('Percentile ranks aggregation must have field and values or script and values set.');
    }
}
