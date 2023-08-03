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
 * Class representing ip range aggregation.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-iprange-aggregation.html
 */
class Ipv4RangeAggregation extends AbstractAggregation
{
    use BucketingTrait;

    private array $ranges = [];

    /**
     * Inner aggregations container init.
     */
    public function __construct(string $name, ?string $field = null, array $ranges = [])
    {
        parent::__construct($name);

        $this->setField($field);
        foreach ($ranges as $range) {
            if (is_array($range)) {
                $from = $range['from'] ?? null;
                $to = $range['to'] ?? null;
                $this->addRange($from, $to);
            } else {
                $this->addMask($range);
            }
        }
    }

    /**
     * Add range to aggregation.
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

        $this->ranges[] = $range;

        return $this;
    }

    /**
     * Add ip mask to aggregation.
     */
    public function addMask(string $mask): static
    {
        $this->ranges[] = ['mask' => $mask];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'ip_range';
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array
    {
        if ($this->getField() && !empty($this->ranges)) {
            return [
                'field' => $this->getField(),
                'ranges' => array_values($this->ranges),
            ];
        }

        throw new \LogicException('Ip range aggregation must have field set and range added.');
    }
}
