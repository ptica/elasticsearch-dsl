<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Aggregation\Bucketing;

use ONGR\ElasticsearchDSL\Aggregation\AbstractAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Type\BucketingTrait;

/**
 * Class representing geo distance aggregation.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-geodistance-aggregation.html
 */
class GeoDistanceAggregation extends AbstractAggregation
{
    use BucketingTrait;

    private mixed $origin;
    private ?string $distanceType;
    private ?string $unit;
    private array $ranges = [];

    /**
     * Inner aggregations container init.
     *
     * @param string $name
     * @param string $field
     * @param mixed  $origin
     * @param array  $ranges
     * @param string $unit
     * @param string $distanceType
     */
    public function __construct(string $name, ?string $field = null, mixed $origin = null, array $ranges = [], ?string $unit = null, ?string $distanceType = null)
    {
        parent::__construct($name);

        $this->setField($field);
        $this->setOrigin($origin);
        foreach ($ranges as $range) {
            $from = $range['from'] ?? null;
            $to = $range['to'] ?? null;
            $this->addRange($from, $to);
        }

        $this->setUnit($unit);
        $this->setDistanceType($distanceType);
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(mixed $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getDistanceType(): ?string
    {
        return $this->distanceType;
    }

    public function setDistanceType(?string $distanceType): static
    {
        $this->distanceType = $distanceType;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     *
     * @return $this
     */
    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Add range to aggregation.
     *
     * @throws \LogicException
     */
    public function addRange(mixed $from = null, mixed $to = null): static
    {
        $range = array_filter(
            [
                'from' => $from,
                'to' => $to,
            ],
            static fn($v): bool => !is_null($v)
        );

        if (empty($range)) {
            throw new \LogicException('Either from or to must be set. Both cannot be null.');
        }

        $this->ranges[] = $range;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array
    {
        $data = [];

        if ($this->getField()) {
            $data['field'] = $this->getField();
        } else {
            throw new \LogicException('Geo distance aggregation must have a field set.');
        }

        if ($this->getOrigin()) {
            $data['origin'] = $this->getOrigin();
        } else {
            throw new \LogicException('Geo distance aggregation must have an origin set.');
        }

        if ($this->getUnit()) {
            $data['unit'] = $this->getUnit();
        }

        if ($this->getDistanceType()) {
            $data['distance_type'] = $this->getDistanceType();
        }

        $data['ranges'] = $this->ranges;

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'geo_distance';
    }
}
