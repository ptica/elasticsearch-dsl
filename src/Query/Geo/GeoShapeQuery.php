<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Query\Geo;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\ParametersTrait;

/**
 * Represents Elasticsearch "geo_shape" query.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-shape-query.html
 */
class GeoShapeQuery implements BuilderInterface
{
    use ParametersTrait;

    final public const INTERSECTS = 'intersects';
    final public const DISJOINT = 'disjoint';
    final public const WITHIN = 'within';
    final public const CONTAINS = 'contains';

    private array $fields = [];

    public function __construct(array $parameters = [])
    {
        $this->setParameters($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'geo_shape';
    }

    public function addGeometry(
        string $field,
        mixed $geometry,
        string $relation = self::INTERSECTS,
        array $parameters = []
    ): static {
        $this->fields[$field] = [
                'shape' => $geometry,
                'relation' => $relation,
            ] + $parameters;

        return $this;
    }

    /**
     * Add geo-shape provided filter.
     *
     * @param string $field Field name.
     * @param string $type Shape type.
     * @param array $coordinates Shape coordinates.
     * @param string $relation Spatial relation.
     * @param array $parameters Additional parameters.
     */
    public function addShape(
        string $field,
        string $type,
        array $coordinates,
        string $relation = self::INTERSECTS,
        array $parameters = []
    ): static {
        $filter = array_merge(
            $parameters,
            [
                'type' => $type,
                'coordinates' => $coordinates,
            ]
        );

        $this->fields[$field] = [
            'shape' => $filter,
            'relation' => $relation,
        ];

        return $this;
    }

    /**
     * Add geo-shape pre-indexed filter.
     *
     * @param string $field Field name.
     * @param string $id The ID of the document that containing the pre-indexed shape.
     * @param string $type Name of the index where the pre-indexed shape is.
     * @param string $index Index type where the pre-indexed shape is.
     * @param string $relation Spatial relation.
     * @param string $path The field specified as path containing the pre-indexed shape.
     * @param array $parameters Additional parameters.
     */
    public function addPreIndexedShape(
        string $field,
        string $id,
        string $type,
        string $index,
        string $path,
        string $relation = self::INTERSECTS,
        array $parameters = []
    ): static {
        $filter = array_merge(
            $parameters,
            [
                'id' => $id,
                'type' => $type,
                'index' => $index,
                'path' => $path,
            ]
        );

        $this->fields[$field] = [
            'indexed_shape' => $filter,
            'relation' => $relation,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $output = $this->processArray($this->fields);

        return [$this->getType() => $output];
    }
}
