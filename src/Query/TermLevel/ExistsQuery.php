<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Query\TermLevel;

use ONGR\ElasticsearchDSL\BuilderInterface;

/**
 * Represents Elasticsearch "exists" query.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html
 */
class ExistsQuery implements BuilderInterface
{
    /**
     * Constructor.
     */
    public function __construct(private string $field)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'exists';
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array|\stdClass
    {
        return [
            $this->getType() => [
                'field' => $this->field,
            ],
        ];
    }
}
