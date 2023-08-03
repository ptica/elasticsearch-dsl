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
use ONGR\ElasticsearchDSL\BuilderInterface;

/**
 * Top hits aggregation.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-top-hits-aggregation.html
 */
class TopHitsAggregation extends AbstractAggregation
{
    use MetricTrait;

    /**
     * Number of top matching hits to return per bucket.
     */
    private ?int $size;

    /**
     * The offset from the first result you want to fetch.
     */
    private ?int $from;

    /**
     * @var BuilderInterface[] How the top matching hits should be sorted.
     */
    private array $sorts = [];

    /**
     * Constructor for top hits.
     *
     * @param string $name Aggregation name.
     * @param null|int $size Number of top matching hits to return per bucket.
     * @param null|int $from The offset from the first result you want to fetch.
     * @param null|BuilderInterface $sort How the top matching hits should be sorted.
     */
    public function __construct(string $name, ?int $size = null, ?int $from = null, ?BuilderInterface $sort = null)
    {
        parent::__construct($name);
        $this->setFrom($from);
        $this->setSize($size);
        if ($sort instanceof BuilderInterface) {
            $this->addSort($sort);
        }
    }

    public function getFrom(): ?int
    {
        return $this->from;
    }

    public function setFrom($from): static
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return BuilderInterface[]
     */
    public function getSorts(): array
    {
        return $this->sorts;
    }

    /**
     * @param BuilderInterface[] $sorts
     *
     * @return $this
     */
    public function setSorts(array $sorts): static
    {
        $this->sorts = $sorts;

        return $this;
    }

    public function addSort(BuilderInterface $sort): void
    {
        $this->sorts[] = $sort;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'top_hits';
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array|\stdClass
    {
        $sortsOutput = null;
        $addedSorts = array_filter($this->getSorts());
        if ($addedSorts) {
            foreach ($addedSorts as $sort) {
                $sortsOutput[] = $sort->toArray();
            }
        }

        $output = array_filter(
            [
                'sort' => $sortsOutput,
                'size' => $this->getSize(),
                'from' => $this->getFrom(),
            ],
            static fn($val): bool => $val || is_array($val) || ($val || is_numeric($val))
        );

        return empty($output) ? new \stdClass() : $output;
    }
}
