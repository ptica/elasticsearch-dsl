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
 * Class representing FilterAggregation.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-filter-aggregation.html
 */
class FilterAggregation extends AbstractAggregation
{
    use BucketingTrait;

    protected ?BuilderInterface $filter = null;

    /**
     * Inner aggregations container init.
     */
    public function __construct(string $name, ?BuilderInterface $filter = null)
    {
        parent::__construct($name);

        if ($filter instanceof BuilderInterface) {
            $this->setFilter($filter);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setField(?string $field): static
    {
        throw new \LogicException("Filter aggregation, doesn't support `field` parameter");
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array|\stdClass
    {
        if (!$this->filter) {
            throw new \LogicException(sprintf('Filter aggregation `%s` has no filter added', $this->getName()));
        }

        return $this->getFilter()->toArray();
    }

    /**
     * Returns a filter.
     */
    public function getFilter(): ?BuilderInterface
    {
        return $this->filter;
    }

    /**
     * @return $this
     */
    public function setFilter(BuilderInterface $filter): static
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'filter';
    }
}
