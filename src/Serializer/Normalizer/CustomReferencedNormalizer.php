<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Normalizer used with referenced normalized objects.
 */
final class CustomReferencedNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    private CustomNormalizer $customNormalizer;

    private array $references = [];

    public function __construct()
    {
        $this->customNormalizer = new CustomNormalizer();
    }

    public function normalize(mixed $object, string $format = null, array $context = []): array|bool|string|int|float|null|\ArrayObject
    {
        if ($this->supportsNormalization($object, $format)) {
            $object->setReferences($this->references);
            $data = $this->customNormalizer->normalize($object, $format, $context);
            $this->references = array_merge($this->references, $object->getReferences());

            return $data;
        }
        throw new \InvalidArgumentException('Unsupported object type for normalization.');
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        return $this->customNormalizer->denormalize($data, $type, $format, $context);
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        if ($this->customNormalizer instanceof SerializerAwareInterface) {
            $this->customNormalizer->setSerializer($serializer);
        }
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            NormalizableInterface::class => true,
            DenormalizableInterface::class => true,
        ];
    }

    /**
     * @param mixed       $data   Data to denormalize from
     * @param string      $type   The class to which the data should be denormalized
     * @param string|null $format The format being deserialized from
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $this->customNormalizer->supportsDenormalization($data, $type, $format, $context);
    }

    /**
     * @param mixed       $data   Data to normalize
     * @param string|null $format The format being (de-)serialized from or into
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $this->customNormalizer->supportsNormalization($data, $format, $context);
    }
}
