<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Sort;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\ParametersTrait;

/**
 * Holds all the values required for basic sorting.
 */
class FieldSort implements BuilderInterface
{
    use ParametersTrait;

    final public const ASC = 'asc';
    final public const DESC = 'desc';

    private ?BuilderInterface $nestedFilter = null;

    /**
     * @param string $field  Field name.
     * @param string $order  Order direction.
     * @param array  $params Params that can be set to field sort.
     */
    public function __construct(private string $field, private ?string $order = null, array $params = [])
    {
        $this->setParameters($params);
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function setOrder(string $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function getNestedFilter(): ?BuilderInterface
    {
        return $this->nestedFilter;
    }

    public function setNestedFilter(BuilderInterface $nestedFilter): static
    {
        $this->nestedFilter = $nestedFilter;

        return $this;
    }

    /**
     * Returns element type.
     */
    public function getType(): string
    {
        return 'sort';
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        if ($this->order) {
            $this->addParameter('order', $this->order);
        }

        if ($this->nestedFilter instanceof BuilderInterface) {
            $this->addParameter('nested', $this->nestedFilter->toArray());
        }

        return [
            $this->field => $this->getParameters() ?: [],
        ];
    }
}
