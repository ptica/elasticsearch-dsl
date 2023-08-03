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
 * Class representing ReverseNestedAggregation.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-reverse-nested-aggregation.html
 */
class ReverseNestedAggregation extends AbstractAggregation
{
    use BucketingTrait;

    private ?string $path;

    /**
     * Inner aggregations container init.
     */
    public function __construct(string $name, ?string $path = null)
    {
        parent::__construct($name);

        $this->setPath($path);
    }

    /**
     * Return path.
     *
     * @return string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'reverse_nested';
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array
    {
        $output = [];
        if ($this->getPath()) {
            $output = ['path' => $this->getPath()];
        }

        return $output;
    }
}
