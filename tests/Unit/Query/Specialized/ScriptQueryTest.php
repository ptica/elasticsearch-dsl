<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Query\Specialized;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use ONGR\ElasticsearchDSL\Query\Specialized\ScriptQuery;

class ScriptQueryTest extends TestCase
{
    /**
     * Data provider for testToArray().
     */
    public static function getArrayDataProvider(): array
    {
        return [
            'simple_script' => [
                "doc['num1'].value > 1",
                [],
                ['script' => ['inline' => "doc['num1'].value > 1"]],
            ],
            'script_with_parameters' => [
                "doc['num1'].value > param1",
                ['params' => ['param1' => 5]],
                ['script' => ['inline' => "doc['num1'].value > param1", 'params' => ['param1' => 5]]],
            ],
        ];
    }

    /**
     * Test for toArray().
     *
     * @param string $script     Script
     * @param array  $parameters Optional parameters
     * @param array  $expected   Expected values
     */
    #[DataProvider('getArrayDataProvider')]
    public function testToArray($script, $parameters, $expected): void
    {
        $filter = new ScriptQuery($script, $parameters);
        $result = $filter->toArray();
        $this->assertEquals(['script' => $expected], $result);
    }
}
