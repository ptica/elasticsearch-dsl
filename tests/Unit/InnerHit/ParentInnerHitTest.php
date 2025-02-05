<?php

namespace ONGR\ElasticsearchDSL\Tests\Unit\InnerHit;

use PHPUnit\Framework\TestCase;
use ONGR\ElasticsearchDSL\InnerHit\ParentInnerHit;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Search;

class ParentInnerHitTest extends TestCase
{
    public function testToArray(): void
    {
        $query = new TermQuery('foo', 'bar');
        $search = new Search();
        $search->addQuery($query);


        $hit = new ParentInnerHit('test', 'acme', $search);
        $expected = [
            'type' => [
                'acme' => [
                    'query' => $query->toArray(),
                ],
            ],
        ];
        $this->assertEquals($expected, $hit->toArray());
    }
}
