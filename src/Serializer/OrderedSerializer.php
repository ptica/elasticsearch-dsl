<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Serializer;

use ONGR\ElasticsearchDSL\Serializer\Normalizer\OrderedNormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Custom serializer which orders data before normalization.
 */
class OrderedSerializer extends Serializer
{
    /**
     * {@inheritdoc}
     */
    public function normalize(
        $data,
        string $format = null,
        array $context = []
    ): array|bool|string|int|float|null|\ArrayObject {
        return parent::normalize(
            is_array($data) ? $this->order($data) : $data,
            $format,
            $context
        );
    }

    /**
     * Orders objects if can be done.
     */
    private function order(array $data): array
    {
        $filteredData = $this->filterOrderable($data);

        if (!empty($filteredData)) {
            uasort(
                $filteredData,
                static fn(OrderedNormalizerInterface $a, OrderedNormalizerInterface $b):
                bool => $a->getOrder() > $b->getOrder()
            );

            return array_merge($filteredData, array_diff_key($data, $filteredData));
        }

        return $data;
    }

    /**
     * Filters out data which can be ordered.
     *
     * @param array $array Data to filter out.
     */
    private function filterOrderable(array $array): array
    {
        return array_filter(
            $array,
            static fn($value): bool => $value instanceof OrderedNormalizerInterface
        );
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {
        return parent::denormalize(
            is_array($data) ? $this->order($data) : $data,
            $type,
            $format,
            $context
        );
    }
}
