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
 * Class representing date range aggregation.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-daterange-aggregation.html
 */
class DateRangeAggregation extends AbstractAggregation
{
    use BucketingTrait;

    private ?string $format;
    private array $ranges = [];
    private bool $keyed = false;

    public function __construct(string $name, ?string $field = null, ?string $format = null, array $ranges = [], bool $keyed = false)
    {
        parent::__construct($name);

        $this->setField($field);
        $this->setFormat($format);
        $this->setKeyed($keyed);
        foreach ($ranges as $range) {
            $from = $range['from'] ?? null;
            $to = $range['to'] ?? null;
            $key = $range['key'] ?? null;
            $this->addRange($from, $to, $key);
        }
    }

    /**
     * Sets if result buckets should be keyed.
     *
     * @return DateRangeAggregation
     */
    public function setKeyed(bool $keyed): static
    {
        $this->keyed = $keyed;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): void
    {
        $this->format = $format;
    }

    /**
     * Add range to aggregation.
     *
     * @param string|null $from
     * @param string|null $to
     * @param string|null $key
     *
     * @return $this
     *
     * @throws \LogicException
     */
    public function addRange(mixed $from = null, mixed $to = null, mixed $key = null): static
    {
        $range = array_filter(
            [
                'from' => $from,
                'to' => $to,
                'key' => $key,
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
    public function getArray(): array|\stdClass
    {
        if ($this->getField() && $this->getFormat() && !empty($this->ranges)) {
            return [
                'format' => $this->getFormat(),
                'field' => $this->getField(),
                'ranges' => $this->ranges,
                'keyed' => $this->keyed,
            ];
        }

        throw new \LogicException('Date range aggregation must have field, format set and range added.');
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'date_range';
    }
}
