<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Query;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\ParametersTrait;

/**
 * Represents Elasticsearch "match_all" query.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-all-query.html
 */
class MatchAllQuery implements BuilderInterface
{
    use ParametersTrait;

    /**
     * @param array $parameters Additional parameters.
     */
    public function __construct(array $parameters = [])
    {
        $this->setParameters($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array|\stdClass
    {
        $params = $this->getParameters();
        return [$this->getType() => !empty($params) ? $params : new \stdClass()];
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'match_all';
    }
}
