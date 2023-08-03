<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Query\Compound;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\Query\Compound\FunctionScoreQuery;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;

/**
 * Tests for FunctionScoreQuery.
 */
class FunctionScoreQueryTest extends TestCase
{
    /**
     * Data provider for testAddRandomFunction.
     */
    public static function addRandomFunctionProvider(): array
    {
        return [
            // Case #0. No seed.
            [
                'seed' => null,
                'expectedArray' => [
                    'query' => [],
                    'functions' => [
                        [
                            'random_score' => [],
                        ],
                    ],
                ],
            ],
            // Case #1. With seed.
            [
                'seed' => 'someSeed',
                'expectedArray' => [
                    'query' => [],
                    'functions' => [
                        [
                            'random_score' => [ 'seed' => 'someSeed'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Tests addRandomFunction method.
     *
     * @param array $expectedArray
     */
    #[DataProvider('addRandomFunctionProvider')]
    public function testAddRandomFunction(mixed $seed, $expectedArray): void
    {
        /** @var MatchAllQuery|MockObject $matchAllQuery */
        $matchAllQuery = $this->createMock(MatchAllQuery::class);

        $functionScoreQuery = new FunctionScoreQuery($matchAllQuery);
        $functionScoreQuery->addRandomFunction($seed);

        $this->assertEquals(['function_score' => $expectedArray], $functionScoreQuery->toArray());
    }
    
    /**
     * Tests default argument values.
     */
    public function testAddFieldValueFactorFunction(): void
    {
        /** @var BuilderInterface|MockObject $builderInterface */
        $builderInterface = $this->getMockForAbstractClass(BuilderInterface::class);
        $functionScoreQuery = new FunctionScoreQuery($builderInterface);
        $functionScoreQuery->addFieldValueFactorFunction('field1', 2);
        $functionScoreQuery->addFieldValueFactorFunction('field2', 1.5, 'ln');

        $this->assertEquals(
            [
                'query' => [],
                'functions' => [
                    [
                        'field_value_factor' => [
                            'field' => 'field1',
                            'factor' => 2,
                            'modifier' => 'none',
                        ],
                    ],
                    [
                        'field_value_factor' => [
                            'field' => 'field2',
                            'factor' => 1.5,
                            'modifier' => 'ln',
                        ],
                    ],
                ],
            ],
            $functionScoreQuery->toArray()['function_score']
        );
    }
}
