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
use ONGR\ElasticsearchDSL\BuilderInterface;

/**
 * Class representing filters aggregation.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-filters-aggregation.html
 */
class FiltersAggregation extends AbstractAggregation
{
    use BucketingTrait;

    /**
     * @var BuilderInterface[]
     */
    private ?array $filters = [];

    private bool $anonymous = false;

    /**
     * Inner aggregations container init.
     *
     * @param string             $name
     * @param BuilderInterface[] $filters
     * @param bool               $anonymous
     */
    public function __construct(string $name, array $filters = [], bool $anonymous = false)
    {
        parent::__construct($name);

        $this->setAnonymous($anonymous);
        foreach ($filters as $name => $filter) {
            if ($anonymous) {
                $this->addFilter($filter);
            } else {
                $this->addFilter($filter, $name);
            }
        }
    }

    public function setAnonymous(bool $anonymous): static
    {
        $this->anonymous = $anonymous;

        return $this;
    }

    /**
     * @throws \LogicException
     */
    public function addFilter(BuilderInterface $filter, string $name = ''): static
    {
        if ($this->anonymous === false && empty($name)) {
            throw new \LogicException('In not anonymous filters filter name must be set.');
        } elseif ($this->anonymous === false && !empty($name)) {
            $this->filters['filters'][$name] = $filter->toArray();
        } else {
            $this->filters['filters'][] = $filter->toArray();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array
    {
        return $this->filters;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'filters';
    }
}
