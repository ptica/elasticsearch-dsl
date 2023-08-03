<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Query\Joining;

use ONGR\ElasticsearchDSL\BuilderInterface;
use PHPUnit\Framework\TestCase;
use ONGR\ElasticsearchDSL\Query\Joining\HasParentQuery;

class HasParentQueryTest extends TestCase
{
    /**
     * Tests whether __constructor calls setParameters method.
     */
    public function testConstructor(): void
    {
        $parentQuery = $this->createMock(BuilderInterface::class);
        $query = new HasParentQuery('test_type', $parentQuery, ['test_parameter1']);
        $this->assertEquals(['test_parameter1'], $query->getParameters());
    }
}
