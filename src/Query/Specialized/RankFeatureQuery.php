<?php

namespace ONGR\ElasticsearchDSL\Query\Specialized;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\ParametersTrait;

/**
 * Represents Elasticsearch "rank_feature" query.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-rank-feature-query.html
 */
class RankFeatureQuery implements BuilderInterface
{
    use ParametersTrait;

    public function __construct(private readonly string $field, array $parameters)
    {
        $this->setParameters($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'rank_feature';
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $output = $this->processArray(['field' => $this->field]);

        return [$this->getType() => $output];
    }
}
