<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Aggregation\Pipeline;

/**
 * Class representing Bucket Script Pipeline Aggregation.
 *
 * @link https://goo.gl/miVxcx
 */
class BucketScriptAggregation extends AbstractPipelineAggregation
{
    private mixed $script;

    public function __construct(string $name, $bucketsPath, mixed $script = null)
    {
        parent::__construct($name, $bucketsPath);
        $this->setScript($script);
    }

    /**
     * @return string
     */
    public function getScript(): mixed
    {
        return $this->script;
    }

    public function setScript(mixed $script): static
    {
        $this->script = $script;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'bucket_script';
    }

    /**
     * {@inheritdoc}
     */
    public function getArray(): array|\stdClass
    {
        if (!$this->getScript()) {
            throw new \LogicException(
                sprintf(
                    '`%s` aggregation must have script set.',
                    $this->getName()
                )
            );
        }

        return [
            'buckets_path' => $this->getBucketsPath(),
            'script' => $this->getScript(),
        ];
    }
}
