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
 * Class representing Histogram aggregation.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-histogram-aggregation.html
 */
class HistogramAggregation extends AbstractAggregation
{
    use BucketingTrait;

    final public const DIRECTION_ASC = 'asc';
    final public const DIRECTION_DESC = 'desc';

    protected ?int $interval = null;
    protected ?int $minDocCount = null;
    protected ?array $extendedBounds = null;
    protected ?string $orderMode = null;
    protected ?string $orderDirection = null;
    protected ?bool $keyed = null;

    /**
     * Inner aggregations container init.
     */
    public function __construct(
        string $name,
        ?string $field = null,
        ?int $interval = null,
        ?int $minDocCount = null,
        ?string $orderMode = null,
        ?string $orderDirection = self::DIRECTION_ASC,
        ?int $extendedBoundsMin = null,
        ?int $extendedBoundsMax = null,
        ?bool $keyed = null
    ) {
        parent::__construct($name);

        $this->setField($field);
        $this->setInterval($interval);
        $this->setMinDocCount($minDocCount);
        $this->setOrder($orderMode, $orderDirection);
        $this->setExtendedBounds($extendedBoundsMin, $extendedBoundsMax);
        $this->setKeyed($keyed);
    }

    /**
     * Sets buckets ordering.
     */
    public function setOrder(?string $mode, ?string $direction = self::DIRECTION_ASC): static
    {
        $this->orderMode = $mode;
        $this->orderDirection = $direction;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'histogram';
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array|\stdClass
    {
        $out = array_filter(
            [
                'field' => $this->getField(),
                'interval' => $this->getInterval(),
                'min_doc_count' => $this->getMinDocCount(),
                'extended_bounds' => $this->getExtendedBounds(),
                'keyed' => $this->isKeyed(),
                'order' => $this->getOrder(),
            ],
            static fn($val): bool => $val || is_numeric($val)
        );
        $this->checkRequiredParameters($out, ['field', 'interval']);

        return $out;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }

    public function setInterval(?int $interval): static
    {
        $this->interval = $interval;

        return $this;
    }

    public function getMinDocCount(): ?int
    {
        return $this->minDocCount;
    }

    /**
     * Set limit for document count buckets should have.
     */
    public function setMinDocCount(?int $minDocCount): static
    {
        $this->minDocCount = $minDocCount;

        return $this;
    }

    public function getExtendedBounds(): ?array
    {
        return $this->extendedBounds;
    }

    public function setExtendedBounds(?int $min = null, ?int $max = null): static
    {
        $bounds = array_filter(
            [
                'min' => $min,
                'max' => $max,
            ],
            static function ($item) {
                return $item !== null;
            }
        );
        $this->extendedBounds = $bounds;

        return $this;
    }

    public function isKeyed(): ?bool
    {
        return $this->keyed;
    }

    /**
     * Get response as a hash instead keyed by the buckets keys.
     */
    public function setKeyed(?bool $keyed): static
    {
        $this->keyed = $keyed;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrder(): ?array
    {
        if ($this->orderMode && $this->orderDirection) {
            return [$this->orderMode => $this->orderDirection];
        } else {
            return null;
        }
    }

    /**
     * Checks if all required parameters are set.
     *
     * @param array $data
     * @param array $required
     *
     * @throws \LogicException
     */
    protected function checkRequiredParameters(array $data, array $required): void
    {
        if (count(array_intersect_key(array_flip($required), $data)) !== count($required)) {
            throw new \LogicException('Histogram aggregation must have field and interval set.');
        }
    }
}
