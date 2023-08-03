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
use ONGR\ElasticsearchDSL\Query\TermLevel\TermsQuery;

class TermsQueryTest extends TestCase
{
    /**
     * Tests toArray().
     */
    public function testToArray(): void
    {
        $query = new TermsQuery('user', ['bob', 'elasticsearch']);
        $expected = [
            'terms' => [
                'user' => ['bob', 'elasticsearch'],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }
}
