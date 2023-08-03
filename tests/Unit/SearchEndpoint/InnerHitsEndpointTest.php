<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Unit\SearchEndpoint;

use ONGR\ElasticsearchDSL\BuilderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use PHPUnit\Framework\TestCase;
use ONGR\ElasticsearchDSL\SearchEndpoint\InnerHitsEndpoint;

/**
 * Class AggregationsEndpointTest.
 */
class InnerHitsEndpointTest extends TestCase
{
    /**
     * Tests constructor.
     */
    public function testItCanBeInstantiated(): void
    {
        $this->assertInstanceOf(
            InnerHitsEndpoint::class,
            new InnerHitsEndpoint()
        );
    }

    /**
     * Tests if endpoint returns builders.
     */
    public function testEndpointGetter(): void
    {
        $hitName = 'foo';
        $innerHit = $this->createMock(BuilderInterface::class);
        $endpoint = new InnerHitsEndpoint();
        $endpoint->add($innerHit, $hitName);

        $builders = $endpoint->getAll();

        $this->assertCount(1, $builders);
        $this->assertSame($innerHit, $builders[$hitName]);
    }

    /**
     * Tests normalize method
     */
    public function testNormalization(): void
    {
        $normalizer = $this->createMock(NormalizerInterface::class);
        $innerHit = $this
            ->getMockBuilder(BuilderInterface::class)
            ->addMethods(['getName'])
            ->onlyMethods(['toArray', 'getType'])
            ->getMock();
        $innerHit->expects($this->any())->method('getName')->willReturn('foo');
        $innerHit->expects($this->any())->method('toArray')->willReturn(['foo' => 'bar']);

        $endpoint = new InnerHitsEndpoint();
        $endpoint->add($innerHit, 'foo');

        $expected = [
            'foo' => [
                'foo' => 'bar'
            ]
        ];

        $this->assertEquals(
            $expected,
            $endpoint->normalize($normalizer)
        );
    }
}
