<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Query\FullText;

use PHPUnit\Framework\TestCase;
use ONGR\ElasticsearchDSL\Query\FullText\CommonTermsQuery;

class CommonTermsQueryTest extends TestCase
{
    /**
     * Tests toArray().
     */
    public function testToArray(): void
    {
        $query = new CommonTermsQuery('body', 'this is bonsai cool', ['cutoff_frequency' => 0.01]);
        $expected = [
            'common' => [
                'body' => [
                    'query' => 'this is bonsai cool',
                    'cutoff_frequency' => 0.01,
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }
}
