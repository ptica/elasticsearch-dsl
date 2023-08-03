<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Query;

use PHPUnit\Framework\TestCase;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;

class MatchAllQueryTest extends TestCase
{
    /**
     * Tests toArray().
     */
    public function testToArrayWhenThereAreNoParams(): void
    {
        $query = new MatchAllQuery();
        $this->assertEquals(['match_all' => []], $query->toArray());
    }

    /**
     * Tests toArray().
     */
    public function testToArrayWithParams(): void
    {
        $params = ['boost' => 5];
        $query = new MatchAllQuery($params);
        $this->assertEquals(['match_all' => $params], $query->toArray());
    }
}
