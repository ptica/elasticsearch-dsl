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

    protected ?string $calendarInterval = null;
    protected ?string $fixedInterval = null;
    protected ?string $format = null;

    /**
     * Inner aggregations container init.
     */
    public function __construct(
        string $name,
        ?string $field = null,
        ?string $calendarInterval = null,
        ?string $format = null
    ) {
        parent::__construct($name);

        $this->setField($field);
        $this->setCalendarInterval($calendarInterval);
        $this->setFormat($format);
    }

    public function setFormat(?string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getCalendarInterval(): ?string
    {
        return $this->calendarInterval;
    }

    public function setCalendarInterval(?string $calendarInterval): static
    {
        $this->calendarInterval = $calendarInterval;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFixedInterval(): ?string
    {
        return $this->fixedInterval;
    }

    /**
     * @param string|null $fixedInterval
     */
    public function setFixedInterval(?string $fixedInterval): void
    {
        $this->fixedInterval = $fixedInterval;
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
        if (!$this->getField() || !($this->getCalendarInterval() || $this->getFixedInterval())) {
            throw new \LogicException('Date histogram aggregation must have field and calendar_interval set.');
        }

        $out = [
            'field' => $this->getField(),
        ];

        if ($this->getCalendarInterval()) {
            $out['calendar_interval'] = $this->getCalendarInterval();
        } elseif ($this->getFixedInterval()) {
            $out['fixed_interval'] = $this->getFixedInterval();
        }

        if (!empty($this->format)) {
            $out['format'] = $this->format;
        }

        return $out;
    }
}
