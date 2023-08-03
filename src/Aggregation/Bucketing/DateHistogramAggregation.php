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
 * @link https://goo.gl/hGCdDd
 */
class DateHistogramAggregation extends AbstractAggregation
{
    use BucketingTrait;

    protected ?string $interval;
    protected ?string $format;

    /**
     * Inner aggregations container init.
     */
    public function __construct(string $name, ?string $field = null, ?string $interval = null, ?string $format = null)
    {
        parent::__construct($name);

        $this->setField($field);
        $this->setInterval($interval);
        $this->setFormat($format);
    }

    public function getInterval(): ?string
    {
        return $this->interval;
    }

    public function setInterval(?string $interval): static
    {
        $this->interval = $interval;

        return $this;
    }

    public function setFormat(?string $format): static
    {
        $this->format = $format;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'date_histogram';
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array|\stdClass
    {
        if (!$this->getField() || !$this->getInterval()) {
            throw new \LogicException('Date histogram aggregation must have field and interval set.');
        }

        $out = [
            'field' => $this->getField(),
            'interval' => $this->getInterval(),
        ];

        if (!empty($this->format)) {
            $out['format'] = $this->format;
        }

        return $out;
    }
}
