<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Aggregation;

use ONGR\ElasticsearchDSL\BuilderBag;
use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\NameAwareTrait;
use ONGR\ElasticsearchDSL\NamedBuilderInterface;
use ONGR\ElasticsearchDSL\ParametersTrait;

/**
 * AbstractAggregation class.
 */
abstract class AbstractAggregation implements NamedBuilderInterface
{
    use ParametersTrait;
    use NameAwareTrait;

    private ?string $field = null;
    private ?BuilderBag $aggregations = null;

    /**
     * Inner aggregations container init.
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(?string $field): static
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Adds a sub-aggregation.
     */
    public function addAggregation(AbstractAggregation $abstractAggregation): static
    {
        if (!$this->aggregations instanceof BuilderBag) {
            $this->aggregations = $this->createBuilderBag();
        }

        $this->aggregations->add($abstractAggregation);

        return $this;
    }

    /**
     * Creates BuilderBag new instance.
     */
    private function createBuilderBag(): BuilderBag
    {
        return new BuilderBag();
    }

    /**
     * Returns sub aggregation.
     * @param string $name Aggregation name to return.
     */
    public function getAggregation(string $name): ?BuilderInterface
    {
        if ($this->aggregations && $this->aggregations->has($name)) {
            return $this->aggregations->get($name);
        } else {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array|\stdClass
    {
        $array = $this->getArray();
        $result = [
            $this->getType() => is_array($array) ? $this->processArray($array) : $array,
        ];

        if ($this->supportsNesting()) {
            $nestedResult = $this->collectNestedAggregations();

            if (!empty($nestedResult)) {
                $result['aggregations'] = $nestedResult;
            }
        }

        return $result;
    }

    /**
     * @return array|\stdClass
     */
    abstract public function getArray(): array|\stdClass;

    /**
     * Abstract supportsNesting method.
     *
     * @return bool
     */
    abstract protected function supportsNesting(): bool;

    /**
     * Process all nested aggregations.
     */
    protected function collectNestedAggregations(): array
    {
        $result = [];
        /** @var AbstractAggregation $aggregation */
        foreach ($this->getAggregations() as $aggregation) {
            $result[$aggregation->getName()] = $aggregation->toArray();
        }

        return $result;
    }

    /**
     * Returns all sub aggregations.
     *
     * @return BuilderBag[]|NamedBuilderInterface[]
     */
    public function getAggregations(): array
    {
        if ($this->aggregations instanceof BuilderBag) {
            return $this->aggregations->all();
        } else {
            return [];
        }
    }
}
