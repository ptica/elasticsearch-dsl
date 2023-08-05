<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\SearchEndpoint;

use ONGR\ElasticsearchDSL\BuilderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Search highlight dsl endpoint.
 */
class HighlightEndpoint extends AbstractSearchEndpoint
{
    /**
     * Endpoint name
     */
    final public const NAME = 'highlight';

    private ?BuilderInterface $highlight = null;

    /**
     * @var mixed Key for highlight storing.
     */
    private mixed $key;

    /**
     * {@inheritdoc}
     */
    public function normalize(
        NormalizerInterface $normalizer,
        $format = null,
        array $context = []
    ): array|string|int|float|bool|\ArrayObject|null {
        return $this->highlight?->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function add(BuilderInterface $builder, mixed $key = null): mixed
    {
        if ($this->highlight) {
            throw new \OverflowException('Only one highlight can be set');
        }

        $this->key = $key;
        $this->highlight = $builder;

        return $key;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(?string $boolType = null): array
    {
        return [$this->key => $this->highlight];
    }

    public function getHighlight(): ?BuilderInterface
    {
        return $this->highlight;
    }
}
