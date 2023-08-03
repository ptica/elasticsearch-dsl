<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL;

/**
 * Container for named builders.
 */
class BuilderBag
{
    /**
     * @var BuilderInterface[]
     */
    private array $bag = [];

    /**
     * @param BuilderInterface[] $builders
     */
    public function __construct($builders = [])
    {
        foreach ($builders as $builder) {
            $this->add($builder);
        }
    }

    /**
     * Adds a builder.
     */
    public function add(BuilderInterface $builder): string
    {
        $name = method_exists($builder, 'getName') ? $builder->getName() : bin2hex(random_bytes(30));

        $this->bag[$name] = $builder;

        return $name;
    }

    /**
     * Checks if builder exists by a specific name.
     */
    public function has(string $name): bool
    {
        return isset($this->bag[$name]);
    }

    /**
     * Removes a builder by name.
     */
    public function remove(string $name): void
    {
        unset($this->bag[$name]);
    }

    /**
     * Clears contained builders.
     */
    public function clear(): void
    {
        $this->bag = [];
    }

    /**
     * Returns a builder by name.
     */
    public function get(string $name): ?BuilderInterface
    {
        return $this->bag[$name] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array|\stdClass
    {
        $output = [];
        foreach ($this->all() as $builder) {
            $output = array_merge($output, $builder->toArray());
        }

        return $output;
    }

    /**
     * Returns all builders contained.
     *
     * @return BuilderInterface[]
     */
    public function all(mixed $type = null): array
    {
        return array_filter(
            $this->bag,
            /** @var BuilderInterface $builder */
            static fn(BuilderInterface $builder): bool => $type === null || $builder->getType() == $type
        );
    }
}
