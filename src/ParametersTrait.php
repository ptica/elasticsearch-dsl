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
 * A trait which handles the behavior of parameters in queries, filters, etc.
 */
trait ParametersTrait
{
    private array $parameters = [];

    /**
     * Removes parameter.
     */
    public function removeParameter(string $name): void
    {
        if ($this->hasParameter($name)) {
            unset($this->parameters[$name]);
        }
    }

    /**
     * Checks if parameter exists.
     */
    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    /**
     * Returns one parameter by it's name.
     */
    public function getParameter(string $name): mixed
    {
        return $this->parameters[$name] ?? null;
    }

    /**
     * Returns an array of all parameters.
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): static
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function addParameter(string $name, mixed $value): void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Returns given array merged with parameters.
     */
    protected function processArray(array $array = []): array
    {
        return array_merge($array, $this->parameters);
    }
}
