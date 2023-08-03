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

/**
 * Class representing geo bounds aggregation.
 *
 * @link http://goo.gl/aGqw7Y
 */
class GeoBoundsAggregation extends AbstractAggregation
{
    use MetricTrait;

    private bool $wrapLongitude = true;

    /**
     * Inner aggregations container init.
     */
    public function __construct(string $name, ?string $field = null, bool $wrapLongitude = true)
    {
        parent::__construct($name);

        $this->setField($field);
        $this->setWrapLongitude($wrapLongitude);
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array|\stdClass
    {
        $data = [];
        if ($this->getField()) {
            $data['field'] = $this->getField();
        } else {
            throw new \LogicException('Geo bounds aggregation must have a field set.');
        }

        $data['wrap_longitude'] = $this->isWrapLongitude();

        return $data;
    }

    public function isWrapLongitude(): bool
    {
        return $this->wrapLongitude;
    }

    /**
     * @return $this
     */
    public function setWrapLongitude(bool $wrapLongitude): static
    {
        $this->wrapLongitude = $wrapLongitude;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'geo_bounds';
    }
}
