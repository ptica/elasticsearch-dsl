<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Query\Compound;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\ParametersTrait;

/**
 * Represents Elasticsearch "function_score" query.
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html
 */
class FunctionScoreQuery implements BuilderInterface
{
    use ParametersTrait;

    private ?array $functions = null;

    public function __construct(private readonly BuilderInterface $query, array $parameters = [])
    {
        $this->setParameters($parameters);
    }

    /**
     * Returns the query instance.
     */
    public function getQuery(): BuilderInterface
    {
        return $this->query;
    }

    /**
     * Creates field_value_factor function.
     */
    public function addFieldValueFactorFunction(
        string $field,
        float $factor,
        string $modifier = 'none',
        BuilderInterface $query = null,
        mixed $missing = null
    ): static {
        $function = [
            'field_value_factor' => array_filter(
                [
                    'field' => $field,
                    'factor' => $factor,
                    'modifier' => $modifier,
                    'missing' => $missing,
                ]
            ),
        ];

        $this->applyFilter($function, $query);

        $this->functions[] = $function;

        return $this;
    }

    /**
     * Modifier to apply filter to the function score function.
     */
    private function applyFilter(array &$function, BuilderInterface $query = null): void
    {
        if ($query instanceof BuilderInterface) {
            $function['filter'] = $query->toArray();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array|\stdClass
    {
        $query = [
            'query' => $this->query->toArray(),
            'functions' => $this->functions,
        ];

        $output = $this->processArray($query);

        return [$this->getType() => $output];
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'function_score';
    }

    /**
     * Add decay function to function score. Weight and query are optional.
     */
    public function addDecayFunction(
        string $type,
        string $field,
        array $function,
        array $options = [],
        BuilderInterface $query = null,
        ?int $weight = null
    ): static {
        $function = array_filter(
            [
                $type => array_merge(
                    [$field => $function],
                    $options
                ),
                'weight' => $weight,
            ]
        );

        $this->applyFilter($function, $query);

        $this->functions[] = $function;

        return $this;
    }

    /**
     * Adds function to function score without decay function. Influence search score only for specific query.
     */
    public function addWeightFunction(float $weight, BuilderInterface $query = null): static
    {
        $function = [
            'weight' => $weight,
        ];

        $this->applyFilter($function, $query);

        $this->functions[] = $function;

        return $this;
    }

    /**
     * Adds random score function. Seed is optional.
     */
    public function addRandomFunction(mixed $seed = null, BuilderInterface $query = null): static
    {
        $function = [
            'random_score' => $seed ? ['seed' => $seed] : new \stdClass(),
        ];

        $this->applyFilter($function, $query);

        $this->functions[] = $function;

        return $this;
    }

    /**
     * Adds script score function.
     */
    public function addScriptScoreFunction(
        string $source,
        array $params = [],
        array $options = [],
        BuilderInterface $query = null
    ): static {
        $function = [
            'script_score' => [
                'script' =>
                    array_filter(
                        array_merge(
                            [
                                'lang' => 'painless',
                                'source' => $source,
                                'params' => $params,
                            ],
                            $options
                        )
                    ),
            ],
        ];

        $this->applyFilter($function, $query);
        $this->functions[] = $function;

        return $this;
    }

    /**
     * Adds custom simple function. You can add to the array whatever you want.
     */
    public function addSimpleFunction(array $function): static
    {
        $this->functions[] = $function;

        return $this;
    }
}
