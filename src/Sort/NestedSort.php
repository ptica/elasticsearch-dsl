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
 * Represents Elasticsearch "nested" sort filter.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/filter-dsl-nested-filter.html
 */
class NestedSort implements BuilderInterface
{
    use ParametersTrait;

    private ?BuilderInterface $nestedFilter = null;

    /**
     * @param string $path
     * @param BuilderInterface|null $filter
     */
    public function __construct(
        private $path,
        private readonly ?BuilderInterface $filter = null,
        array $parameters = []
    ) {
        $this->setParameters($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'nested';
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $output = [
            'path'   => $this->path,
        ];

        if ($this->filter instanceof BuilderInterface) {
            $output['filter'] = $this->filter->toArray();
        }

        if ($this->nestedFilter instanceof BuilderInterface) {
            $output[$this->getType()] = $this->nestedFilter->toArray();
        }

        return $this->processArray($output);
    }

    /**
     * Returns nested filter object.
     */
    public function getFilter(): ?BuilderInterface
    {
        return $this->filter;
    }

    /**
     * Returns path this filter is set for.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getNestedFilter(): ?BuilderInterface
    {
        return $this->nestedFilter;
    }

    /**
     * @return $this
     */
    public function setNestedFilter(BuilderInterface $nestedFilter)
    {
        $this->nestedFilter = $nestedFilter;

        return $this;
    }
}
