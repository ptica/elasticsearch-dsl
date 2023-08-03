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
 * Class representing ChildrenAggregation.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-children-aggregation.html
 */
class ChildrenAggregation extends AbstractAggregation
{
    use BucketingTrait;

    private ?string $children;

    public function __construct(string $name, ?string $children = null)
    {
        parent::__construct($name);

        $this->setChildren($children);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'children';
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array|\stdClass
    {
        if (count($this->getAggregations()) == 0) {
            throw new \LogicException(sprintf('Children aggregation `%s` has no aggregations added', $this->getName()));
        }

        return ['type' => $this->getChildren()];
    }

    public function getChildren(): ?string
    {
        return $this->children;
    }

    public function setChildren(?string $children): static
    {
        $this->children = $children;

        return $this;
    }
}
