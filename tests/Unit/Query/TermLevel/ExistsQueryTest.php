<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Query\TermLevel;

use PHPUnit\Framework\TestCase;
use ONGR\ElasticsearchDSL\Query\TermLevel\ExistsQuery;

/**
 * Unit test for ExistsQuery.
 */
class ExistsQueryTest extends TestCase
{
    /**
     * Tests toArray() method.
     */
    public function testToArray(): void
    {
        $query = new ExistsQuery('bar');
        $this->assertEquals(['exists' => ['field' => 'bar']], $query->toArray());
    }
}
